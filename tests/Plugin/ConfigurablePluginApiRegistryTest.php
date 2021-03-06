<?php

namespace Jascha030\Tests\Plugin;

use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementHookableInterfaceException;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementProviderInterfaceException;
use Jascha030\PluginLib\Plugin\ConfigurablePluginApiRegistry;
use PHPUnit\Framework\TestCase;

class ConfigurablePluginApiRegistryTest extends TestCase
{
    /**
     * @throws DoesNotImplementHookableInterfaceException
     * @throws DoesNotImplementProviderInterfaceException
     */
    public function test__construct()
    {
        $plugin = new ConfigurablePluginApiRegistry('test', dirname(__FILE__, 2));
//        self::assertInstanceOf($plugin, Plugin)
    }
}
