<?php

namespace Jascha030\PluginLib\Container\Config;

use Jascha030\PluginLib\Entity\Post\PostTypeInterface;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementHookableInterfaceException;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementProviderInterfaceException;
use Jascha030\PluginLib\Service\Hookable\HookableAfterInitInterface;
use Jascha030\PluginLib\Service\Hookable\HookableInterface;
use Jascha030\PluginLib\Service\Hookable\LazyHookableInterface;
use Jascha030\PluginLib\Service\Provider\WordpressProvider;
use Pimple\Container;
use Pimple\Container as PimpleContainer;
use Pimple\Psr11\ServiceLocator;
use Pimple\ServiceProviderInterface;

/**
 * Class Config
 *
 * @package Jascha030\PluginLib\Container\Config
 */
final class Config implements ConfigInterface
{
    private string $pluginName;

    private string $pluginFile;

    private array $postTypes = [];

    private array $hookables = [];

    private array $serviceProviders = [];

    private string $pluginPrefix;

    /**
     * Config constructor.
     *
     * @param  string  $name
     * @param  string  $file
     */
    public function __construct(string $name, string $file)
    {
        $this->pluginName = $name;

        $this->pluginPrefix = str_replace(' ', '', strtolower($name));

        $this->pluginFile = $file;
    }

    /**
     * @return array|string[]
     */
    public function getPostTypes(): array
    {
        return $this->postTypes;
    }

    /**
     * @param  array|string[]  $postTypes
     *
     * @return Config
     */
    public function setPostTypes(array $postTypes): self
    {
        $this->postTypes = $postTypes;

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getHookables(): array
    {
        return $this->hookables;
    }

    /**
     * @param  array|string[]  $hookables
     *
     * @return Config
     */
    public function setHookables(array $hookables): self
    {
        $this->hookables = $hookables;

        return $this;
    }

    /**
     * @return array
     */
    public function getServiceProviders(): array
    {
        return $this->serviceProviders;
    }

    /**
     * @param  array|array[]  $serviceProviders
     *
     * @return Config
     */
    public function setServiceProviders(array $serviceProviders): self
    {
        $this->serviceProviders = $serviceProviders;

        return $this;
    }

    /**
     * @return string
     */
    public function getPluginName(): string
    {
        return $this->pluginName;
    }

    /**
     * @return string
     */
    public function getPluginFile(): string
    {
        return $this->pluginFile;
    }

    /**
     * @return string
     */
    public function getPluginPrefix(): string
    {
        return $this->pluginPrefix;
    }

    /**
     * @param  PimpleContainer  $container
     *
     * @throws DoesNotImplementHookableInterfaceException
     * @throws DoesNotImplementProviderInterfaceException
     */
    public function configure(PimpleContainer $container): void
    {
        $hookables = $this->getHookables();
        $postTypes = $this->getPostTypes();

        $this->injectServiceProviders($container);

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
        }

        $postTypesArray = [];

        foreach ($postTypes as $postType) {
            if (is_array($postType)) {
                $postTypesArray[$postType[0]] = $postType;
            }

            if (is_subclass_of($postType, PostTypeInterface::class)) {
                $postTypesArray[$postType] = $postType;
            }
        }

        $container['hookable.reference'] = $reference;
        $container['hookable.afterInit'] = $afterInitHookables;
        $container['plugin.postTypes']   = $postTypesArray;

        $container['hookable.locator'] = static function (Container $container) {
            $lazyHookables    = array_keys($container['hookable.reference']);
            $hookableServices = array_merge(
                $lazyHookables,
                $container['hookable.afterInit'],
                $container['plugin.postTypes']
            );

            return new ServiceLocator($container, $hookableServices);
        };
    }

    private function injectServiceProviders(Container $container): void
    {
        $serviceProviders = $this->getServiceProviders();
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
                $container->register(new $provider($this->getPluginName(), $this->getPluginFile()), $arguments);
                continue;
            }

            $container->register(new $provider(), $arguments);
        }
    }
}
