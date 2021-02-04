<?php

namespace Jascha030\PluginLib\Container;

use Psr\Container\ContainerInterface;

/**
 * Interface ExpandableContainerInterface
 * Extends Psr-11's ContainerInterface with an add method.
 *
 * This makes it possible to use any Container implementing PSR-11, as long as you provide your plugin the ability to add items.
 * To keep things simple we use pimple/pimple as default.
 *
 * @package Jascha030\PluginLib\Container
 */
interface ExpandableContainerInterface extends ContainerInterface
{
    /**
     * Enforces Psr-11 extended with an add method.
     *
     * @param  string  $alias
     * @param  mixed  $item
     */
    public function add(string $alias, $item): void;
}