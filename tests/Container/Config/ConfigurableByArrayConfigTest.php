<?php

namespace Jascha030\Tests\Container\Config;

use Jascha030\PluginLib\Container\Config\ConfigInterface;
use Jascha030\PluginLib\Container\Config\ConfigurableByArrayConfig;
use PHPUnit\Framework\TestCase;

class ConfigurableByArrayConfigTest extends TestCase
{
    public function testConstruct(): ConfigInterface
    {
        $configArray = require dirname(__FILE__, 3) . '/config/testConfig.php';
        $config      = new ConfigurableByArrayConfig($configArray, __FILE__);

        self::assertInstanceOf(ConfigInterface::class, $config);

        return $config;
    }
}
