<?php

namespace Jascha030\PluginLib\Hookable;

use Closure;
use Exception;
use Symfony\Component\Uid\Uuid;

final class DeferringHookableManager implements HookableManagerInterface
{
    public const FILTER = 0;
    public const ACTION = 1;

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
     * @var \Jascha030\PluginLib\Container\WordpressFilterContainerInterface
     */
    private $container;

    /**
     * @var array Track hooked method closures
     */
    private $hookedMethods = [];

    public function __construct(\Jascha030\PluginLib\Container\WordpressFilterContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     *
     * @param  string  $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->container[$id]);
    }

    public function registerHookable(string $className, array $classArguments): void
    {
        if (! is_subclass_of($className, DeferrableAbstract::class)) {
            throw self::invalidClassException($className);
        }

        if (! $this->container->has($className)) {
            $this->container->add(
                $className,
                function ($c) use ($className, $classArguments) {
                    return new $className(...$classArguments);
                }
            );
        }

        $this->addAll($className);
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

    public function addFilter(
        string $tag,
        string $class,
        string $method,
        int $priority = 10,
        int $arguments = 1
    ): void {
        $closure = $this->wrapFilter($tag, $class, $method, $this->generateHookIdentifier());

        \add_filter($tag, $closure, $priority, $arguments);
    }

    /**
     * {@inheritDoc}
     *
     * @param  string  $id
     * @return mixed
     */
    public function get(string $id)
    {
        return $this->container[$id];
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

    public function __toString(): string
    {
        $string = '';

        foreach ($this->hookedMethods as $tag => $hooked) {
            $string .= sprintf('<tr><td>%s</td><td>%s</td></tr>', $tag, implode('<br />', $hooked));
        }

        return $string;
    }

    private static function invalidClassException(string $class): Exception
    {
        $msg = sprintf(self::INVALID_CLASS_MESSAGE, $class, DeferrableAbstract::class);

        return new \Exception($msg);
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

    /**
     * Wraps class and method in a Closure that calls our container and checks if our hook identifier still exists,
     * In that case, the Class is retrieved from our container to execute hooked method,
     *
     * If not, the method  wil not be executed. This is a workaround for newer wordpress versions,
     * in which it is basically impossible to unhook a closure by reference. (It's possible, it's not failsafe)
     *
     * @param  string  $tag
     * @param  string  $service
     * @param  string  $method
     * @param  string  $identifier
     * @return Closure
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    private function wrapFilter(string $tag, string $service, string $method, string $identifier): Closure
    {
        $this->hookedMethods[$tag][$identifier] = [$service, $method];

        return function (...$args) use ($service, $method, $identifier) {
            if (array_key_exists($identifier, $this->hookedMethods)) {
                return $this->get($service)->{$method}(...$args);
            }
        };
    }
}
