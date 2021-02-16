<?php

namespace Jascha030\PluginLib\Container;

use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ContainerFactory
 * @package Jascha030\PluginLib\Container
 */
class ContainerFactory
{
    /**
     * @param  array|ServiceProviderInterface[]  $serviceProviders
     * @return Psr11Container
     */
    public function __invoke(array $serviceProviders = []): Psr11Container
    {
        $container = new Container();

        foreach ($serviceProviders as $provider) {
            $provider->register($container);
        }

        return new Psr11Container($container);
    }
}