<?php

declare(strict_types=1);

namespace Jascha030\PluginLib\Container;

use Pimple\Container as PimpleContainer;
use Psr\Container\ContainerInterface;

/**
 * Class Psr11Container
 *
 * PSR-11 Compliant wrapper for Pimple Container.
 * The one pimple provides is final, this one can be extended.
 *
 * @package Jascha030\PluginLib
 * @author Jascha030 <contact@jaschavanaalst.nl>
 */
class Psr11Container implements ContainerInterface
{
    protected $container;

    public function __construct(PimpleContainer $container = null)
    {
        $this->container = $container ?? new PimpleContainer();
    }

    /**
     * {@inheritDoc}
     */
    final public function get($id)
    {
        return $this->container[$id];
    }

    /**
     * {@inheritDoc}
     */
    final public function has($id): bool
    {
        return isset($this->container[$id]);
    }

    /**
     * @param string $id
     * @param mixed $entry
     */
    final public function set(string $id, $entry): void
    {
        $this->container[$id] = $entry;
    }
}
