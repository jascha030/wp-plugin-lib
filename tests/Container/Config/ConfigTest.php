<?php

namespace Jascha030\Tests\Container\Config;

use Jascha030\PluginLib\Container\Config\Config;
use Jascha030\PluginLib\Container\Config\ConfigInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ConfigTest extends TestCase
{
    public const TEST_PLUGIN_NAME = 'Test Plugin';

    final public function testConfigConstruction(): ConfigInterface
    {
        $config = new Config(self::TEST_PLUGIN_NAME, __FILE__);

        self::assertInstanceOf(ConfigInterface::class, $config);

        self::assertEquals(__FILE__, $config->getPluginFile());
        self::assertEquals(self::TEST_PLUGIN_NAME, $config->getPluginName());

        return $config;
    }

    /**
     * @depends testConfigConstruction
     * @depends testSetters
     */
    final public function testAccessMethods(ConfigInterface $config): void
    {
        self::assertEquals(self::TEST_PLUGIN_NAME.'_SET', $config->getPluginName());
        self::assertEquals(__FILE__, $config->getPluginFile());
        self::assertEquals(str_replace(' ', '', strtolower(self::TEST_PLUGIN_NAME)), $config->getPluginPrefix());
        self::assertEquals([], $config->getHookables());
        self::assertEquals([], $config->getServiceProviders());
        self::assertEquals([], $config->getPostTypes());
    }

    /**
     * @depends      testConfigConstruction
     *
     * @noinspection UnnecessaryAssertionInspection
     */
    final public function testSetters(ConfigInterface $config): void
    {
        $prefix = str_replace(' ', '', strtolower(self::TEST_PLUGIN_NAME));

        self::assertInstanceOf(ConfigInterface::class, $config->setPluginName(self::TEST_PLUGIN_NAME.'_SET'));
        self::assertInstanceOf(ConfigInterface::class, $config->setPluginFile(__FILE__));
        self::assertInstanceOf(ConfigInterface::class, $config->setPluginPrefix($prefix));
        self::assertInstanceOf(ConfigInterface::class, $config->setPostTypes([]));
        self::assertInstanceOf(ConfigInterface::class, $config->setServiceProviders([]));
        self::assertInstanceOf(ConfigInterface::class, $config->setHookables([]));
    }
}
