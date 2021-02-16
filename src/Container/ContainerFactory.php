<?php

namespace Jascha030\PluginLib\Container;

use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;

/**
 * Class ContainerFactory
 * @package Jascha030\PluginLib\Container
 */
class ContainerFactory
{
    /**
     * @param  string  $pluginRoot
     * @return Psr11Container
     */
    public function __invoke(string $pluginRoot): Psr11Container
    {
        $container = new Container();

        // todo scan directory for service providers
        // These service providers should add dependencies, not directly hooked to actions/filters.

        // todo: scan directory for hookable classes
        // HookableServices should strictly contain public functions that are hooked to actions/filters.

//        foreach ($serviceProviders as $provider) {
//            $provider->register($container);
//        }

        return new Psr11Container($container);
    }
}