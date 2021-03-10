<?php

namespace Jascha030\PluginLib\Container\Config;

use Pimple\Container as PimpleContainer;

final class ContainerConfig implements ConfigInterface
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function configure(PimpleContainer $container): void
    {
        $container['config'] = $this->config;

        if (isset($config['serviceProviders'])) {
            $this->injectServiceProviders($container, $config['serviceProviders']);
        }

        if (isset($config['hookableServices'])) {
            $this->injectHookableServices($container, $config['hookableServices']);
        }

        if (isset($config['postTypes'])) {
            $this->injectPostTypes($container, $config['postTypes']);
        }
    }

    private function injectPostTypes(Pimple $pimple): void
    {
    }
}
