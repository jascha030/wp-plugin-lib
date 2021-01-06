<?php

namespace Jascha030\PluginLib\Plugin;

use Jascha030\PluginLib\Hookable\HookableAbstract;
use Pimple\Container;

/**
 * Class WordpressPluginAbstract
 *
 * Plugin class interfaces with the wp plugin api.
 * Holds and initialises other hookable classes used by a plugin.
 *
 * @package Jascha030\PluginLib\Plugin
 */
abstract class WordpressPluginAbstract extends HookableAbstract
{
    /**
     * @var Container holds other hookable classes
     */
    private $container;

    /**
     * WordpressPluginAbstract constructor.
     *
     * @param  array  $classes
     * @param  array  $classArguments
     */
    public function __construct(array $classes = [], array $classArguments = [])
    {
        $this->container = new Container();

        $this->hookClasses($classes, $classArguments);

        parent::__construct();
    }

    /**
     * Init all classes with hookable methods
     *
     * @param  array  $classes
     * @param  array  $classArguments
     */
    protected function hookClasses(array $classes, array $classArguments): void
    {
        foreach ($classes as $class) {
            if (is_string($class)) {
                if (isset($classArguments[$class]) && is_array($classArguments[$class])) {
                    $this->container[$class] = new $class(...$classArguments[$class]);
                } else {
                    $this->container[$class] = new $class();
                }
            }

            if (is_object($class)) {
                $this->container[$class] = $class;
            }
        }
    }

    /**
     * Returns entry from container
     *
     * @param  string  $className
     * @return mixed
     */
    final public function get(string $className)
    {
        return $this->container[$className];
    }
}