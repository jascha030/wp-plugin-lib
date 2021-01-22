<?php

namespace Jascha030\PluginLib\Container;

use Pimple\Container as PimpleContainer;

/**
 * Class Psr11FilterContainer
 *
 * @package Jascha030\PluginLib\Container
 */
class Psr11FilterContainer implements WordpressFilterContainerInterface
{
    private $pimple;

    public function __construct(PimpleContainer $pimple)
    {
        $this->pimple = $pimple;
    }

    /**
     * {@inheritDoc}
     *
     * @param  string  $id
     * @return mixed
     * @noinspection MissingReturnTypeInspection
     */
    final public function get(string $id)
    {
        return $this->pimple[$id];
    }

    /**
     * {@inheritDoc}
     *
     * @param  string  $id
     * @return bool
     */
    final public function has(string $id): bool
    {
        return isset($this->pimple[$id]);
    }

    /**
     * {@inheritDoc}
     *
     * @param  string  $name
     * @param  mixed  $item
     */
    final public function add(string $name, $item): void
    {
        $this->pimple[$name] = $item;
    }
}