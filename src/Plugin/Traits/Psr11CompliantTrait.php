<?php

namespace Jascha030\PluginLib\Plugin\Traits;

use Psr\Container\ContainerInterface;

/**
 * Trait Psr11CompliantTrait
 * @package Jascha030\PluginLib\Plugin\Traits
 */
trait Psr11CompliantTrait
{
    public function get(string $id)
    {
        if (! $this->has($id)) {
            throw new \InvalidArgumentException("Invalid identifier {$id}");
        }

        return $this->getContainer()->get($id);
    }

    public function has(string $id): bool
    {
        return $this->getContainer()->has($id);
    }

    abstract protected function getContainer(): ContainerInterface;
}