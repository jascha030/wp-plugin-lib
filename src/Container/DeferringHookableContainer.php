<?php

namespace Jascha030\PluginLib\Container;

use Exception;
use Jascha030\PluginLib\Hookable\DeferrableAbstract;
use Jascha030\PluginLib\Hookable\HookableAbstract;

class DeferringHookableContainer
{
    public const ACTION = 2;
    public const FILTER = 1;

    public const HOOK_TYPES = [
        self::ACTION => 'getActions',
        self::FILTER => 'getFilters'
    ];

    private const INVALID_CLASS_MESSAGE = '%s does not implement %s.';

    final public function registerByMap(string $class, DeferrableAbstract $object = null): void
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

    private static function invalidClassException(string $class): Exception
    {
        $msg = sprintf(self::INVALID_CLASS_MESSAGE, $class, HookableAbstract::class);

        return new \Exception($msg);
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
}