<?php

namespace Jascha030\PluginLib\Functions;

use Jascha030\PluginLib\Container\Config\Config;
use Jascha030\PluginLib\Container\ContainerBuilderInterface as Builder;
use Jascha030\PluginLib\Exception\DoesNotImplementInterfaceException;
use Jascha030\PluginLib\Plugin\FilterManagerInterface;
use Jascha030\PluginLib\Plugin\PluginApiRegistryAbstract;

if (! function_exists('buildPlugin')) {
    /**
     * Creates your plugin or theme class and injects all of it's dependencies
     *
     * @param  Config  $config    Config object containing your Hookable classes, Service Providers and Post types
     * @param  string  $registry  Class name of your main plugin or theme class
     * @param  string  $builder   Custom builder class if you want to use a different Container than Pimple
     *
     * @return FilterManagerInterface
     * @throws DoesNotImplementInterfaceException
     */
    function buildPlugin(Config $config, string $registry, string $builder = Builder::class): FilterManagerInterface
    {
        if (! is_subclass_of(ContainerBuilderInterface::class, $builder)) {
            throw new DoesNotImplementInterfaceException($builder, ContainerBuilderInterface::class);
        }

        if (! is_subclass_of(FilterManagerInterface::class, $registry)) {
            throw new DoesNotImplementInterfaceException($registry, PluginApiRegistryAbstract::class);
        }

        /**
         * Build PSR-11 Container.
         */
        $container = (new $builder())($config);

        /**
         * PluginApiRegistryInterface
         */
        return new $registry($container->get('hookable.reference'),
            $container->get('hookable.afterInit'),
            $container->get('hookable.locator'));
    }
}
