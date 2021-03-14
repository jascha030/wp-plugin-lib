<?php

namespace Jascha030\PluginLib\Container\Config;

use Pimple\Container as PimpleContainer;

interface ConfigInterface
{
    public function configure(PimpleContainer $container): void;
}
