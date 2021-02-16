<?php

namespace Jascha030\PluginLib\Container;

use Jascha030\PluginLib\Manager\Filter\FilterManager;
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
     * @param  array  $hookableServices
     * @return Psr11Container
     */
    public function __invoke(array $serviceProviders = [], array $hookableServices = []): Psr11Container
    {
        $container = new Container();

        foreach ($serviceProviders as $provider) {
            $provider->register($container);
        }

        $container['filters'] = function (Container $container) use ($hookableServices) {
            $manager = new FilterManager($container);

            foreach ($hookableServices as $alias => $service) {
                $manager->registerHookable($alias, $service);
            }
        };

        return new Psr11Container($container);
    }
}