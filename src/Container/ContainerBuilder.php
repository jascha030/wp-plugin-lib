<?php

namespace Jascha030\PluginLib\Container;

use Jascha030\PluginLib\Container\Config\Config;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementHookableInterfaceException;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementProviderInterfaceException;
use Jascha030\PluginLib\Service\Hookable\HookableAfterInitInterface;
use Jascha030\PluginLib\Service\Hookable\HookableInterface;
use Jascha030\PluginLib\Service\Hookable\LazyHookableInterface;
use Jascha030\PluginLib\Service\Provider\WordpressProvider;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;
use Pimple\Psr11\ServiceLocator;
use Pimple\ServiceProviderInterface;

/**
 * Class ContainerFactory
 *
 * @package Jascha030\PluginLib\Container
 */
final class ContainerBuilder implements ContainerBuilderInterface
{
    /**
     * @inheritDoc
     *
     * @param  Config  $config
     * @param  string  $file
     *
     * @return Psr11Container
     * @throws DoesNotImplementHookableInterfaceException
     * @throws DoesNotImplementProviderInterfaceException
     */
    public function __invoke(Config $config): Psr11Container
    {
        $serviceProviders = $config->getServiceProviders();
        $hookables        = $config->getHookables();
        $postTypes        = $config->getPostTypes();

        /**
         * Create the container to be configured.
         * In this case we use Pimple (the simplest one, 9/10 times, wordpress shouldn't require anything fancier).
         */
        $container = new Container();

        if (! in_array(WordpressProvider::class, $serviceProviders, true)) {
            $serviceProviders[WordpressProvider::class] = [];
        }

        /**
         * These service providers should add dependencies and methods that need to be globally available,
         * they should not be hooked directly to WordPress' actions or filters.
         */
        foreach ($serviceProviders as $provider => $arguments) {
            if (! is_subclass_of($provider, ServiceProviderInterface::class)) {
                throw new DoesNotImplementProviderInterfaceException($provider);
            }

            if ($provider === WordpressProvider::class) {
                $container->register(new $provider($config->getPluginName(), $config->getPluginFile()), $arguments);
                continue;
            }

            $container->register(new $provider(), $arguments);
        }

        /**
         * HookableClasses should strictly contain public methods to be hooked to wordpress actions/filters
         * Other methods should be private/protected methods that are used by classes public methods.
         * Globally available methods belong in a service that is provided by a ServiceProvider.
         */
        $afterInitHookables = [];
        $reference          = [];

        if (! empty($hookables)) {
            foreach ($hookables as $className) {
                if (! is_subclass_of($className, HookableInterface::class)) {
                    throw new DoesNotImplementHookableInterfaceException($className);
                }

                if (is_subclass_of($className, LazyHookableInterface::class)) {
                    $reference[$className] = [];
                }

                if (is_subclass_of($className, HookableAfterInitInterface::class)) {
                    $afterInitHookables[] = $className;
                }

                $container[$className] = static function () use ($className) {
                    return new $className();
                };
            }

            $container['hookable.reference'] = $reference;
            $container['hookable.afterInit'] = $afterInitHookables;
            $container['hookable.locator']   = function (Container $container) {
                $lazyHookables    = array_keys($container['hookable.reference']);
                $hookableServices = array_merge($lazyHookables, $container['hookable.afterInit']);

                return new ServiceLocator($container, $hookableServices);
            };
        }

        if (! empty($postTypes)) {
            $container['plugin.postTypes'] = $postTypes;
        }

        /**
         * All of the dependencies are added here and afterwards we wrap the container in a PSR11 compliant container.
         * From this point onwards we can consider the container closed for new additions.
         */
        return new Psr11Container($container);
    }
}
