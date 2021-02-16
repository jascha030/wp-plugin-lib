<?php

namespace Jascha030\PluginLib\Manager\Filter;

use Closure;
use Exception;
use Jascha030\PluginLib\Hookable\LazyHookableAbstract;
use Pimple\Container;
use Symfony\Component\Uid\Uuid;

final class FilterManager implements FilterManagerInterface
{
    public const FILTER = 0;
    public const ACTION = 1;

    /**
     * Prefixes for Wordpress filter methods
     */
    private const FILTER_TYPES = [
        self::ACTION => 'action',
        self::FILTER => 'filter'
    ];

    /**
     * See methods defined in DeferrableAbstract.
     */
    private const FILTER_RETRIEVAL_METHODS = [
        self::ACTION => 'getActions',
        self::FILTER => 'getFilters'
    ];

    /**
     * String template for Exception messages
     */
    private const INVALID_CLASS_MESSAGE = '%s does not implement %s.';

    /**
     * @var Container|null
     */
    private $container;

    /**
     * Store hooked methods and their uuid's
     *
     * @var array
     */
    private $hookedMethods = [];

    /**
     * Store all uuid's associated with a HookableClass
     *
     * @var array
     */
    private $hookableReference = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    private static function invalidClassException(string $class): Exception
    {
        $msg = sprintf(self::INVALID_CLASS_MESSAGE, $class, LazyHookableAbstract::class);

        return new Exception($msg);
    }

    public function has(string $id): bool
    {
        return isset($this->container[$id]);
    }

    /**
     * Register hookable class and hook its methods lazily
     *
     * @param  string  $alias
     * @param  mixed|string|Closure|null  $calls
     * @throws Exception
     */
    public function registerHookable(string $alias, $calls = null): void
    {
        if ($calls instanceof Closure) {
            $this->container[$alias] = $calls;
            return;
        }

        if (! $calls) {
            $calls = $alias;
        }

        $className = $calls;

        if (! is_subclass_of($className, LazyHookableAbstract::class)) {
            // todo: Psr11 Exception
            throw new \RuntimeException("Invalid class {$className}");
        }

        $this->container[$alias] = function () use ($className) {
            return new $className();
        };

        $this->hookableReference[$alias] = [];
    }

    public function addAction(
        string $tag,
        string $class,
        string $method,
        int $priority = 10,
        int $arguments = 1
    ): void {
        $closure = $this->wrapFilter($tag, $class, $method, $this->generateHookIdentifier());

        \add_action($tag, $closure, $priority, $arguments);
    }

    /**
     * Wraps class and method in a Closure that calls our container and checks if our hook identifier still exists,
     * In that case, the Class is retrieved from our container to execute hooked method,
     *
     * If not, the method  wil not be executed. This is a workaround for newer wordpress versions,
     * in which it is basically impossible to unhook a closure by reference. (It's possible, it's not failsafe)
     *
     * @param  string  $tag
     * @param  string  $alias
     * @param  string  $method
     * @param  string  $identifier
     * @return Closure
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    private function wrapFilter(string $tag, string $alias, string $method, string $identifier): Closure
    {
        $this->hookedMethods[$identifier]             = compact('tag', 'alias', 'method');
        $this->hookableReference[$alias][$identifier] = $identifier;

        return function (...$args) use ($alias, $method, $identifier) {
            if (array_key_exists($identifier, $this->hookedMethods)) {
                return $this->get($alias)->{$method}(...$args);
            }
        };
    }

    /**
     * Generate a string to keep track of methods that are hooked as closures
     *
     * @return string
     */
    public function generateHookIdentifier(): string
    {
        return Uuid::v4()->toRfc4122();
    }

    public function addFilter(
        string $tag,
        string $class,
        string $method,
        int $priority = 10,
        int $arguments = 1
    ): void {
        $closure = $this->wrapFilter($tag, $class, $method, $this->generateHookIdentifier());

        add_filter($tag, $closure, $priority, $arguments);
    }

    public function __toString(): string
    {
        $string = '';

        foreach ($this->hookedMethods as $tag => $hooked) {
            $string .= sprintf('<tr><td>%s</td><td>%s</td></tr>', $tag, implode('<br />', $hooked));
        }

        return $string;
    }

    /**
     * @inheritDoc
     */
    public function removeHookable(string $alias): bool
    {
        foreach ($this->hookableReference[$alias] as $identifier) {
            foreach ($this->hookedMethods as $tag) {
                if (isset($this->hookedMethods[$identifier])) {
                    unset($this->hookedMethods[$identifier]);
                }

                unset($this->hookableReference[$alias][$identifier]);
            }
        }
    }

    /**
     * Hooks all Actions/Filters defined in Deferrable.
     *
     * @param  string  $serviceClass
     */
    private function addAll(string $serviceClass): void
    {
        foreach (self::FILTER_RETRIEVAL_METHODS as $key => $method) {
            $addMethod = 'add'.ucfirst(self::FILTER_TYPES[$key]);

            // calls static::getAction() or static::getFilter() on Deferrable.
            $hooks = $serviceClass::{$method}();

            // Iterates hook types and checks service for methods to hook.
            foreach ($hooks as $tag => $parameters) {
                // Check if single or multiple methods are added to hook.
                if (is_array($parameters) && is_array($parameters[0])) {
                    foreach ($parameters as $params) { // multiple
                        $this->{$addMethod}($serviceClass, $tag, $params, $key);
                    }
                } else { // single
                    $this->{$addMethod}($serviceClass, $tag, $parameters, $key);
                }
            }
        }
    }
}
