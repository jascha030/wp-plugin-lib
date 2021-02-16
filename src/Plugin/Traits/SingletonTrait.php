<?php

namespace Jascha030\PluginLib\Plugin\Traits;

/**
 * Trait SingletonTrait
 * Obsolete but still used often for simpler use cases, so I'll save the trait.
 *
 * @package Jascha030\PluginLib\Plugin\Traits
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
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}