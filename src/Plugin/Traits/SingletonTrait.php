<?php

/** @noinspection PhpMissingFieldTypeInspection */

namespace Jascha030\PluginLib\Plugin\Traits;

/**
 * Trait SingletonTrait
 * Obsolete but still used often for simpler use cases, so I'll save the trait.
 */
trait SingletonTrait
{
    private static $instance;

    /**
     * Return the singleton instance of using class.
     *
     * @return static
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * These methods are private to prevent any other form of mutability or construction.
     */
    private function __construct()
    {
    }

    private function __wakeup()
    {
    }

    private function __clone()
    {
    }
}
