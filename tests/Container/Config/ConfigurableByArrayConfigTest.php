<?php

namespace Jascha030\Tests\Container\Config;

use Jascha030\PluginLib\Container\Config\ConfigInterface;
use Jascha030\PluginLib\Container\Config\ConfigurableByArrayConfig;
use PHPUnit\Framework\TestCase;

final class ConfigurableByArrayConfigTest extends TestCase
{
    public function testConstruct(): ConfigInterface
    {
        $configArray = require dirname(__FILE__, 3) . '/config/testConfig.php';
        $config      = new ConfigurableByArrayConfig($configArray, __FILE__);

        self::assertInstanceOf(ConfigInterface::class, $config);

        return $config;
    }

    /**
     * @depends testConstruct
     *
     * @param ConfigInterface $config
     */
    public function testGetConfig(ConfigInterface $config): void
    {
        self::assertTrue([], $config->getConfig());
    }
}
