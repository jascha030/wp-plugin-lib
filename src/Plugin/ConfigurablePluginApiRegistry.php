<?php

namespace Jascha030\PluginLib\Plugin;

use Jascha030\PluginLib\Container\Config\ConfigInterface;
use Jascha030\PluginLib\Container\Config\ConfigurableByArrayConfig;
use Jascha030\PluginLib\Container\ContainerBuilder;
use Psr\Container\ContainerInterface;

/**
 * Class ConfigurablePluginApiRegistry
 * @package Jascha030\PluginLib\Plugin
 */
class ConfigurablePluginApiRegistry extends PluginApiRegistryAbstract
{
    private string $pluginFile;

    /**
     * @var string
     */
    private string $name;

    private ?string $configPath;

    /**
     * ConfigurablePluginApiRegistry constructor.
     *
     * @param string      $name
     * @param string      $pluginFile
     * @param string|null $configPath
     */
    public function __construct(string $name, string $pluginFile, string $configPath = null)
    {
        $this->name = $name;

        if (!is_file($pluginFile)) {
            throw new \InvalidArgumentException("Could not read provided \"\$pluginFile\", invalid path: \"{$pluginFile}\"");
        }

        $this->pluginFile = $pluginFile;

        $this->configPath = $configPath;

        $container = $this->createContainer();

        parent::__construct(
            $container->get('hookable.locator'),
            $container->get('hookable.reference'),
            $container->get('hookable.afterInit'),
            $container->get('postTypes')
        );

        $this->setContainer($container);
    }

    /**
     * Used to prefix plugin hook tags to prevent collision with other plugins.
     * @return string
     */
    final public function pluginSlug(): string
    {
        return str_replace(' ', '_', strtolower($this->name));
    }

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

            $path = dirname($this->pluginFile) . '/config/plugin.php';
        }

        if (is_file($path)) {
            $configArray = include $path;

            if (! is_array($configArray)) {
                throw new \RuntimeException('Invalid config data, should be of type array.');
            }
        }

        return $configArray ?? [];
    }

    /**
     * @return ConfigInterface
     */
    private function getConfig(): ConfigInterface
    {
        $config = $this->getConfigFromFile();

        \apply_filters('configure_container_service_providers', $config['serviceProviders'] ?? []);
        \apply_filters('configure_hookable_services', $config['hookableService'] ?? []);
        \apply_filters('configure_post_types', $config['postTypes'] ?? []);

        return new ConfigurableByArrayConfig($config, $this->getPluginFile());
    }

    public function getPluginFile(): string
    {
        return $this->pluginFile;
    }
}
