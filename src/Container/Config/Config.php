<?php

namespace Jascha030\PluginLib\Container\Config;

/**
 * Class Config
 *
 * @package Jascha030\PluginLib\Container\Config
 */
class Config
{
    /**
     * @var string
     */
    private string $pluginName;

    /**
     * @var string
     */
    private string $pluginFile;

    /**
     * @var array
     */
    private array $postTypes = [];

    /**
     * @var array
     */
    private array $hookables = [];

    /**
     * @var array
     */
    private array $serviceProviders = [];

    /**
     * @var string
     */
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
    final public function setPostTypes(array $postTypes): self
    {
        $this->postTypes = $postTypes;

        return $this;
    }

    /**
     * @return array|string[]
     */
    final public function getHookables(): array
    {
        return $this->hookables;
    }

    /**
     * @param  array|string[]  $hookables
     *
     * @return Config
     */
    final public function setHookables(array $hookables): self
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
     * @param  array|array[]  $serviceProviders
     *
     * @return Config
     */
    final public function setServiceProviders(array $serviceProviders): self
    {
        $this->serviceProviders = $serviceProviders;

        return $this;
    }

    /**
     * @return string
     */
    final public function getPluginName(): string
    {
        return $this->pluginName;
    }

    /**
     * @return string
     */
    final public function getPluginFile(): string
    {
        return $this->pluginFile;
    }

    /**
     * @return string
     */
    final public function getPluginPrefix(): string
    {
        return $this->pluginPrefix;
    }
}
