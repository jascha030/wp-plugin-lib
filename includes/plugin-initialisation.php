<?php

namespace Jascha030\PluginLib\Functions;

use Jascha030\PluginLib\Container\Config\ConfigInterface;
use Jascha030\PluginLib\Container\ContainerBuilder as Builder;
use Jascha030\PluginLib\Container\ContainerBuilderInterface;
use Jascha030\PluginLib\Exception\DoesNotImplementInterfaceException;
use Jascha030\PluginLib\Plugin\PluginApiRegistryInterface;

if (! function_exists('buildPlugin')) {
    /**
     * Creates your plugin or theme class and injects all of it's dependencies
     *
     * @param  ConfigInterface  $config  Config object containing your Hookables, Providers and Post types
     * @param  string           $registry  Class name of your main plugin or theme class
     * @param  string           $builder  Custom builder class if you want to use a different Container than Pimple
     *
     * @return PluginApiRegistryInterface
     * @throws DoesNotImplementInterfaceException
     */
    function buildPlugin(
        ConfigInterface $config,
        string $registry,
        string $builder = Builder::class
    ): PluginApiRegistryInterface {
        if (! is_subclass_of($builder, ContainerBuilderInterface::class)) {
            throw new DoesNotImplementInterfaceException($builder, ContainerBuilderInterface::class);
        }

        if (! is_subclass_of($registry, PluginApiRegistryInterface::class)) {
            throw new DoesNotImplementInterfaceException($registry, PluginApiRegistryInterface::class);
        }

        $container = (new $builder())($config);

        /**
         * Plugin or Theme
         */
        $registry = new $registry(
            $container->get('hookable.locator'),
            $container->get('hookable.reference'),
            $container->get('hookable.afterInit'),
            $container->get('plugin.postTypes')
        );

        $registry->setContainer($container);

        return $registry;
    }
}
