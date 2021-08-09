<?php

namespace Jascha030\PluginLib\Container;

use Jascha030\PluginLib\Container\Config\ConfigInterface;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;

/**
 * Class ContainerFactory.
 */
final class ContainerBuilder implements ContainerBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ConfigInterface $config): Psr11Container
    {
        $container = new Container();

        $config->configure($container);

        return new Psr11Container($container);
    }
}
