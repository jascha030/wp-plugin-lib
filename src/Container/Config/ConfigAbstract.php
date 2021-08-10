<?php

namespace Jascha030\PluginLib\Container\Config;

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
     * @return array|string[]
     */
    final public function getPostTypes(): array
    {
        return $this->postTypes;
    }

    final public function setPostTypes(array $postTypes): ConfigInterface
    {
        $this->postTypes = $postTypes;

        return $this;
    }

    final public function getHookables(): array
    {
        return $this->hookables;
    }

    final public function setHookables(array $hookables): ConfigInterface
    {
        $this->hookables = $hookables;

        return $this;
    }

    final public function getServiceProviders(): array
    {
        return $this->serviceProviders;
    }

    final public function setServiceProviders(array $serviceProviders): ConfigInterface
    {
        $this->serviceProviders = $serviceProviders;

        return $this;
    }
}
