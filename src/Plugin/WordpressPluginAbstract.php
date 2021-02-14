<?php

declare(strict_types=1);

namespace Jascha030\PluginLib\Plugin;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
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
    private $providers;
    private $container;
    private $psr11;

    public function __construct(Container $container, array $providers = [])
    {
        $this->container = $container;

        $this->providers = $providers;
    }

    /**
     * @param  string  $id
     * @return mixed
     */
    public function get(string $id)
    {
        if (! $this->has($id)) {
            throw new \InvalidArgumentException("Invalid identifier {$id}");
        }

        return $this->psr11->get($id);
    }

    /**
     * @param  string  $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return $this->psr11->has($id);
    }

    public function run(): void
    {
        foreach ($this->providers as $provider) {
            if (is_subclass_of($provider, ServiceProviderInterface::class)) {
                $provider->register($this->container);
            }
        }

        $this->psr11     = new \Pimple\Psr11\Container($this->container);
        $this->container = null;
    }
}