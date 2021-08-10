<?php

namespace Jascha030\PluginLib\Container;

use Jascha030\PluginLib\Container\Config\ConfigInterface;
use Psr\Container\ContainerInterface;

/**
 * Interface ContainerBuilderInterface.
 */
interface ContainerBuilderInterface
{
    /**
     * Registers all of the plugin or theme's dependencies to a Psr11 compliant container.
     */
    public function __invoke(ConfigInterface $config): ContainerInterface;
}
