<?php

namespace Jascha030\PluginLib\Container;

use Pimple\Container as PimpleContainer;

/**
 * Trait PimpleAsPsr11Trait
 *
 * @package Jascha030\PluginLib\Container
 */
trait PimpleAsPsr11Trait
{
    /**
     * @var PimpleContainer|null
     */
    private $containerInstance;

    /**
     * @param  PimpleContainer  $container
     */
    final public function setContainer(PimpleContainer $container): void
    {
        $this->containerInstance = $container;
    }

    /**
     * @param  string  $id
     * @return mixed
     * @noinspection MissingReturnTypeInspection
     */
    final public function get(string $id)
    {
        return $this->containerInstance[$id];
    }

    /**
     * @param  string  $id
     * @return bool
     */
    final public function has(string $id): bool
    {
        return isset($this->containerInstance[$id]);
    }
}
