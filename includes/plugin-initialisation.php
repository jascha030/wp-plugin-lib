<?php

use Jascha030\PackageTest\Hookable\TestingAfterInitHookable;
use Jascha030\PackageTest\Hookable\TestingHookable;
use Jascha030\PackageTest\PackageTestPlugin;
use Jascha030\PluginLib\Container\Config\Config;
use Jascha030\PluginLib\Container\ContainerBuilder as Builder;
use Jascha030\PluginLib\Plugin\PluginApiRegistryAbstract;
use Jascha030\PluginLib\Exception\DoesNotImplementInterfaceException;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementProviderInterfaceException;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementHookableInterfaceException;

if (! function_exists('buildPlugin')) {
    /**
     * Create's your plugin or theme class and injects all of it's dependencies
     *
     * @param  Config  $config        Config object containing your Hookable classes, Service Providers and Post types
     * @param  string  $pluginClass   Class name of your main plugin or theme class
     * @param  string  $file          Main plugin or theme file (using this in that file and passing __FILE__ is recommended)
     * @param  string  $builderClass  Custom builder class if you want to use a different Container than Pimple
     *
     * @return FilterManagerInterface
     * @throws DoesNotImplementHookableInterfaceException
     * @throws DoesNotImplementProviderInterfaceException
     *
     * @throws DoesNotImplementInterfaceException
     */
    function buildPlugin(
        Config $config,
        string $pluginClass,
        string $file,
        string $builderClass = Builder::class
    ): FilterManagerInterface {
        $config = (new Config('Test package plugin', __FILE__))->setHookables([
            TestingHookable::class, TestingAfterInitHookable::class
        ]);

        $container = (new $builderClass())($config);

        if (isset($container)) {
            if (! is_subclass_of(FilterManagerInterface::class, pluginClass)) {
                throw new DoesNotImplementInterfaceException($pluginClass, PluginApiRegistryAbstract::class);
            }

            $plugin = new $pluginClass($container->get('hookable.reference'),
                $container->get('hookable.afterInit'),
                $container->get('hookable.locator'));

            return $plugin;
        }
    }
}
