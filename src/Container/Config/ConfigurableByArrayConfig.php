<?php

namespace Jascha030\PluginLib\Container\Config;

final class ConfigurableByArrayConfig extends Config
{
    private array $config;

    public function __construct(array $config, string $pluginFile)
    {
        $this->config = $config;

        parent::__construct($config['name'], $pluginFile);

        $this->setProviders($this->config['providers'] ?? []);
        $this->setHookables($this->config['hookables'] ?? []);
        $this->setPostTypes($this->config['postTypes'] ?? []);
    }

    public function getPluginConfig(): array
    {
        return $this->config;
    }
}