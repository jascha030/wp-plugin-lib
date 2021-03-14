<?php

namespace Jascha030\PluginLib\Container\Config;

use Jascha030\PluginLib\Entity\Post\PostType;

abstract class ConfigAbstract implements ConfigInterface
{
    private string $pluginName;

    private string $pluginFile;

    private string $pluginPrefix;

    private array $postTypes = [];

    private array $hookables = [];

    private array $serviceProviders = [];

    final public function getPluginName(): string
    {
        return $this->pluginName;
    }

    final public function setPluginName(string $pluginName): ConfigInterface
    {
        $this->pluginName = $pluginName;

        return $this;
    }

    final public function getPluginFile(): string
    {
        return $this->pluginFile;
    }

    final public function setPluginFile(string $pluginFile): ConfigInterface
    {
        $this->pluginFile = $pluginFile;

        return $this;
    }

    final public function getPluginPrefix(): string
    {
        return $this->pluginPrefix;
    }

    final public function setPluginPrefix(string $pluginPrefix): ConfigInterface
    {
        $this->pluginPrefix = $pluginPrefix;

        return $this;
    }

    /**
     * @return array|PostType|string[]
     */
    final public function getPostTypes(): array
    {
        return $this->postTypes;
    }

    /**
     * @param array $postTypes
     *
     * @return ConfigInterface
     */
    final public function setPostTypes(array $postTypes): ConfigInterface
    {
        $this->postTypes = $postTypes;

        return $this;
    }

    /**
     * @return array
     */
    final public function getHookables(): array
    {
        return $this->hookables;
    }

    /**
     * @param array $hookables
     *
     * @return ConfigInterface
     */
    final public function setHookables(array $hookables): ConfigInterface
    {
        $this->hookables = $hookables;

        return $this;
    }

    /**
     * @return array
     */
    final public function getServiceProviders(): array
    {
        return $this->serviceProviders;
    }

    /**
     * @param array $serviceProviders
     *
     * @return ConfigInterface
     */
    final public function setServiceProviders(array $serviceProviders): ConfigInterface
    {
        $this->serviceProviders = $serviceProviders;

        return $this;
    }
}
