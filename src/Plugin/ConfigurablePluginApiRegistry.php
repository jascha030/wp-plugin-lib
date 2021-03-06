<?php

namespace Jascha030\PluginLib\Plugin;

use Jascha030\PluginLib\Container\Config\Config;
use Jascha030\PluginLib\Container\ContainerBuilder;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementHookableInterfaceException;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementProviderInterfaceException;
use Psr\Container\ContainerInterface;

/**
 * Class ConfigurablePluginApiRegistry
 * @package Jascha030\PluginLib\Plugin
 */
class ConfigurablePluginApiRegistry extends PluginApiRegistryAbstract
{
    private string $pluginFile;

    /**
     * @var mixed|null
     */
    private array $config;

    /**
     * @var string
     */
    private string $name;

    private ?string $configPath;

    /**
     * ConfigurablePluginApiRegistry constructor.
     *
     * @param  string       $name
     * @param  string|null  $configPath
     *
     * @throws DoesNotImplementHookableInterfaceException
     * @throws DoesNotImplementProviderInterfaceException
     */
    public function __construct(string $name, ?string $configPath = null)
    {
        $this->name = $name;

        $this->configPath = $configPath;

        $container = $this->createContainer();

        parent::__construct($container->get('hookable.locator'),
            $container->get('hookable.reference'),
            $container->get('hookable.afterInit'),
            $container->get('plugin.postTypes'));

        $this->setContainer($container);
    }

    /**
     * @throws DoesNotImplementHookableInterfaceException
     * @throws DoesNotImplementProviderInterfaceException
     */
    private function createContainer(): ContainerInterface
    {
        $config = $this->getConfig();

        return (new ContainerBuilder())($config);
    }

    /**
     * @return array
     */
    private function getConfigFromFile(): array
    {
        $path = $this->configPath;

        if (! isset($this->configPath)) {
            if (! is_readable($this->pluginFile)) {
                throw new \RuntimeException("Invalid plugin path '{$this->pluginFile}'");
            }

            $path = dirname($this->pluginFile).'/config/plugin.php';
        }

        if (is_readable($path)) {
            $configArray = include $path;

            if (! is_array($configArray)) {
                throw new \RuntimeException('Invalid config data, should be of type array.');
            }
        }

        return $configArray ?? [];
    }

    /**
     * @return Config
     */
    private function getConfig(): Config
    {
        $config = $this->getConfigFromFile();

        $serviceProviders = \apply_filters('configure_container_service_providers', $config['serviceProviders'] ?? []);

        $hookableServices = \apply_filters('configure_hookable_services', $config['hookableService'] ?? []);

        $postTypes = \apply_filters('configure_post_types', $config['postTypes'] ?? []);

        return (new Config($this->name, $this->pluginFile))->setServiceProviders($serviceProviders)
                                                           ->setHookables($hookableServices)
                                                           ->setPostTypes($postTypes)
            ;
    }
}
