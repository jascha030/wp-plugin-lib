<?php

namespace Jascha030\PluginLib\Container\Config;

final class ConfigurableByArrayConfig extends Config implements ConfigFromFileInterface
{
    private array $config;

    public function __construct(array $config, string $pluginFile)
    {
        $this->config = $config;

        parent::__construct($config['name'], $pluginFile);

        $this->setServiceProviders($this->config['providers'] ?? []);
        $this->setHookables($this->config['hookables'] ?? []);
        $this->setPostTypes($this->config['postTypes'] ?? []);
    }

    public function getConfigArray(): array
    {
        return $this->config;
    }
}
