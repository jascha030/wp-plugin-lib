<?php

namespace Jascha030\Tests\Container\Config;

use Jascha030\PluginLib\Container\Config\Config;
use Jascha030\PluginLib\Container\Config\ConfigInterface;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public const TEST_PLUGIN_NAME = 'TestPlugin';

    final public function testConstruct(): ConfigInterface
    {
        $config = new Config(self::TEST_PLUGIN_NAME, __FILE__);

        self::assertInstanceOf(ConfigInterface::class, $config);

        return $config;
    }

    /**
     * @depends testConstruct
     *
     * @param  ConfigInterface  $config
     */
    final public function testAccessMethods(ConfigInterface $config): void
    {
        self::assertEquals(self::TEST_PLUGIN_NAME, $config->getPluginName());
        self::assertEquals(__FILE__, $config->getPluginFile());

        self::assertEquals([], $config->getHookables());
        self::assertEquals([], $config->getServiceProviders());
        self::assertEquals([], $config->getPostTypes());
    }
}
