<?php

namespace Jascha030\PluginLib\Container;

use Pimple\Container as PimpleContainer;

/**
 * Default Container class
 * @package Jascha030\PluginLib\Container
 */
final class Container implements ExpandableContainerInterface
{
    /**
     * @var PimpleContainer|null
     */
    private $container;

    /**
     * @param  PimpleContainer  $container
     */
    public function setContainer(PimpleContainer $container): void
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function get(string $id)
    {
        return $this->container[$id];
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return isset($this->container[$id]);
    }

    /**
     * @inheritDoc
     */
    public function add(string $alias, $item): void
    {
        $this->container[$alias] = $item;
    }
}
