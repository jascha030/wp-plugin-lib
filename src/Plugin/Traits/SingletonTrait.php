<?php

namespace Jascha030\PluginLib\Plugin\Traits;

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