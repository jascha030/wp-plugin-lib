<?php

namespace Jascha030\PluginLib\Manager\Filter;

use Closure;
use Exception;
use Jascha030\PluginLib\Hookable\LazyHookableAbstract;
use Symfony\Component\Uid\Uuid;

abstract class FilterManager implements FilterManagerInterface
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
     * See methods defined in LazyHookableInterface.
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
     * @var array
     */
    private $hookableServices;

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

    /**
     * Store all hook identifiers under a specific tag
     *
     * @var array
     */
    private $filterTags = [];

    public function __construct(array $hookableServices)
    {
        $this->hookableServices = $hookableServices;
    }

    final public function registerHookables(): void
    {
        foreach ($this->hookableServices as $alias => $service) {
            $this->registerHookable($alias, $service);
        }
    }

    /**
     * Register hookable class and hook its methods lazily
     *
     * @param  string  $alias
     * @param  mixed|string|Closure|null  $calls
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

    /**
     * @param  string  $tag
     * @param  string  $class
     * @param  string  $method
     * @param  int  $prio
     * @param  int  $arguments
     */
    public function addAction(
        string $tag,
        string $class,
        string $method,
        int $prio = 10,
        int $arguments = 1
    ): void {
        $uid     = $this->generateHookIdentifier();
        $closure = $this->wrap($class, $method, $uid);

        \add_action($tag, $closure, $prio, $arguments);
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
        $uuid    = $this->generateHookIdentifier();
        $closure = $this->wrap($class, $method, $uuid);

        \add_filter($tag, $closure, $priority, $arguments);
    }

    public function removeHookedMethodByIdentifier(string $identifier): void
    {
        $hookReference = $this->hookedMethods[$identifier];
        $alias         = $hookReference['alias'];

        $this->hookableReference[$alias][$identifier] = false;
    }

    /**
     * @inheritDoc
     */
    public function removeHookable(string $alias): void
    {
        foreach ($this->hookableReference[$alias] as $identifier) {
            if (isset($this->hookedMethods[$identifier])) {
                unset($this->hookedMethods[$identifier]);
            }

            $this->hookableReference[$alias][$identifier] = false;
        }
    }

    /**
     * @param  string  $class
     * @return Exception
     */
    private static function invalidClassException(string $class): Exception
    {
        $msg = sprintf(self::INVALID_CLASS_MESSAGE, $class, LazyHookableAbstract::class);

        return new Exception($msg);
    }

    /**
     * Wraps class and method in a Closure that calls our container and checks if our hook identifier still exists,
     * In that case, the Class is retrieved from our container to execute hooked method,
     *
     * If not, the method  wil not be executed. This is a workaround for newer wordpress versions,
     * in which it is basically impossible to unhook a closure by reference. (It's possible, it's not failsafe)
     *
     * @param  string  $alias
     * @param  string  $method
     * @param  string  $uid
     * @return Closure
     *
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    private function wrap(string $alias, string $method, string $uid): Closure
    {
        return function (...$args) use ($alias, $method, $uid) {
            if (array_key_exists($uid, $this->hookedMethods)) {
                return $this->get($alias)->{$method}(...$args);
            }
        };
    }

    private function storeFilterReference(
        string $uid,
        string $tag,
        string $alias,
        string $method,
        int $prio,
        int $arguments
    ): void {
        // Store all info on the hooked method.
        $this->hookedMethods[$uid] = compact('tag', 'alias', 'method', 'prio', 'arguments');
        // Add uid to array tracking all methods for a single alias/class.
        $this->hookableReference[$alias][$uid] = true;
        // Add uid to array tracking all methods for a wordpress (action/filter)tag.
        $this->filterTags[$tag][$uid] = true;
    }

    /**
     * Hooks all Actions/Filters defined in Hookable.
     *
     * @param  string  $serviceClass
     */
    private function hookClassMethods(string $serviceClass): void
    {
        foreach (self::FILTER_RETRIEVAL_METHODS as $key => $method) {
            $addMethod = 'add'.ucfirst(self::FILTER_TYPES[$key]);
            // calls static::getAction() or static::getFilter() on lazy hookable.
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
