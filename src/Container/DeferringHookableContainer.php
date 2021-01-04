<?php

namespace Jascha030\PluginLib\Container;

use Closure;
use Exception;
use Jascha030\PluginLib\Hookable\DeferrableAbstract;
use Jascha030\PluginLib\Hookable\HookableAbstract;

class DeferringHookableContainer extends Psr11Container
{
    public const ACTION = 2;
    public const FILTER = 1;

    public const HOOK_TYPES = [
        self::ACTION => 'getActions',
        self::FILTER => 'getFilters'
    ];

    private const INVALID_CLASS_MESSAGE = '%s does not implement %s.';

    protected $container;

    final public function register(string $class, DeferrableAbstract $object = null): void
    {
        if (! is_subclass_of($class, DeferrableAbstract::class)) {
            throw static::invalidClassException($class);
        }

        if (! $this->container->has($class)) {
            $this->container->set(
                $class,
                static function () use ($object, $class) {
                    if (is_null($object)) {
                        $object = new $class();
                    }

                    return $object;
                }
            );
        }

        $this->addAll($class);
    }

    private function addAll(string $serviceClass): void
    {
        foreach (self::HOOK_TYPES as $key => $method) {
            // calls getAction() or getFilter()
            $hooks = $serviceClass::{$method}();

            // Iterates hook types and checks service for hookable methods.
            foreach ($hooks as $tag => $parameters) {
                // Checks if single or multiple class methods are added to hook.
                if (is_array($parameters) && is_array($parameters[0])) {
                    foreach ($parameters as $params) {
                        $this->sanitizeAndAdd($serviceClass, $tag, $params, $key);
                    }
                } else {
                    $this->sanitizeAndAdd($serviceClass, $tag, $parameters, $key);
                }
            }
        }
    }

    private static function invalidClassException(string $class): Exception
    {
        $msg = sprintf(self::INVALID_CLASS_MESSAGE, $class, HookableAbstract::class);

        return new \Exception($msg);
    }

    /**
     * Hook method as action or filter
     *
     * @param  string  $service
     * @param  string  $tag
     * @param  mixed|array|string  $arguments
     * @param  int  $context
     */
    private function sanitizeAndAdd(string $service, string $tag, $arguments, int $context = self::FILTER): void
    {
        // $arguments can be either string containing method name or array containing method, prio and accepted arguments.
        $method            = is_array($arguments) ? $arguments[0] : $arguments;
        $priority          = is_array($arguments) ? $arguments[1] ?? 10 : 10;
        $acceptedArguments = is_array($arguments) ? $arguments[2] ?? 1 : 1;

        $closure = $this->wrapClosure($service, $method);

        if ($context === self::ACTION) {
            \add_action($tag, $closure, $priority, $acceptedArguments);
        }

        if ($context === self::FILTER) {
            \add_filter($tag, $closure, $priority, $acceptedArguments);
        }
    }

    /**
     * Wraps class and method in a Closure
     *
     * @param $service
     * @param $method
     * @return Closure
     */
    private function wrapClosure(string $service, string $method): Closure
    {
        return function (...$args) use ($service, $method) {
            return $this->container->get($service)->{$method}(...$args);
        };
    }
}