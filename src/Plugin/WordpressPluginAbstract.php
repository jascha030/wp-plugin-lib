<?php

declare(strict_types=1);

namespace Jascha030\PluginLib\Plugin;

use Pimple\Psr11\Container as Psr11Container;
use Psr\Container\ContainerInterface;

/**
 * Class WordpressPluginAbstract
 *
 * Plugin class interfaces with the wp plugin api.
 * Holds and initialises other hookable classes used by a plugin.
 *
 * @package Jascha030\PluginLib\Plugin\Traits
 */
abstract class WordpressPluginAbstract implements ContainerInterface
{
    private $container;

    public function __construct(Psr11Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function get(string $id)
    {
        if (! $this->has($id)) {
            throw new \InvalidArgumentException("Invalid identifier {$id}");
        }

        return $this->container->get($id);
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return $this->container->has($id);
    }
}