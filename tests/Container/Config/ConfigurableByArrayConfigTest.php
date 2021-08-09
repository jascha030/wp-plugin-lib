<?php

namespace Jascha030\Tests\Container\Config;

use Jascha030\PluginLib\Container\Config\ConfigFromFileInterface;
use Jascha030\PluginLib\Container\Config\ConfigInterface;
use Jascha030\PluginLib\Container\Config\ConfigurableByArrayConfig;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ConfigurableByArrayConfigTest extends TestCase
{
    public function testConstruct(): ConfigInterface
    {
        $configArray = require \dirname(__FILE__, 3).'/config/testConfig.php';
        $config      = new ConfigurableByArrayConfig($configArray, __FILE__);

        self::assertInstanceOf(ConfigInterface::class, $config);
        self::assertEquals(__FILE__, $config->getPluginFile());

        return $config;
    }

    /**
     * @depends testConstruct
     */
    public function testGetConfig(ConfigFromFileInterface $config): void
    {
        self::assertIsArray($config->getConfigArray());
    }
}
