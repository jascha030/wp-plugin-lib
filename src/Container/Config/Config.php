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
     * @return array|string[]
     */
    public function getPostTypes(): array
    {
        return $this->postTypes;
    }

    /**
     * @param array|string[] $postTypes
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
    public function getHookables(): array
    {
        return $this->hookables;
    }

    /**
     * @param array|string[] $hookables
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
     * @param array|array[] $serviceProviders
     *
     * @return Config
     */
    public function setServiceProviders(array $serviceProviders): self
    {
        $this->serviceProviders = $serviceProviders;

        return $this;
    }
}
