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

    public function __construct(string $name, string $file)
    {
        $this->pluginName = $name;

        $this->pluginPrefix = str_replace(' ', '', strtolower($name));

        $this->pluginFile = $file;
    }

    final public function getPluginName(): string
    {
        return $this->pluginName;
    }

    final public function setPluginName(string $pluginName): void
    {
        $this->pluginName = $pluginName;
    }

    final public function getPluginFile(): string
    {
        return $this->pluginFile;
    }

    final public function setPluginFile(string $pluginFile): void
    {
        $this->pluginFile = $pluginFile;
    }

    final public function getPluginPrefix(): string
    {
        return $this->pluginPrefix;
    }

    final public function setPluginPrefix(string $pluginPrefix): void
    {
        $this->pluginPrefix = $pluginPrefix;
    }

    /**
     * @return array|PostType|string[]
     */
    final public function getPostTypes(): array
    {
        return $this->postTypes;
    }

    /**
     * @param  array  $postTypes
     */
    final public function setPostTypes(array $postTypes): void
    {
        $this->postTypes = $postTypes;
    }

    /**
     * @return array
     */
    final public function getHookables(): array
    {
        return $this->hookables;
    }

    /**
     * @param  array  $hookables
     */
    final public function setHookables(array $hookables): void
    {
        $this->hookables = $hookables;
    }

    /**
     * @return array
     */
    final public function getServiceProviders(): array
    {
        return $this->serviceProviders;
    }

    /**
     * @param  array  $serviceProviders
     */
    final public function setServiceProviders(array $serviceProviders): void
    {
        $this->serviceProviders = $serviceProviders;
    }
}
