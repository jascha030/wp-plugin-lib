<?php

namespace Jascha030\PluginLib\Container\Config;

interface ConfigFromFileInterface extends ConfigInterface
{
    public function getConfigArray(): array;
}
