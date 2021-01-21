<?php

namespace Jascha030\PluginLib\Container;

use Pimple\Container as PimpleContainer;

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
     */
    public function get(string $id)
    {
        return $this->pimple[$id];
    }

    /**
     * {@inheritDoc}
     *
     * @param  string  $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->pimple[$id]);
    }

    /**
     * {@inheritDoc}
     *
     * @param  string  $name
     * @param  mixed  $item
     */
    public function add(string $name, $item): void
    {
        $this->pimple[$name] = $item;
    }
}