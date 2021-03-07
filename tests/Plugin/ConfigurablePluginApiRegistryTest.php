<?php

namespace Jascha030\Tests\Plugin;

use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementHookableInterfaceException;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementProviderInterfaceException;
use Jascha030\PluginLib\Plugin\ConfigurablePluginApiRegistry;
use Jascha030\PluginLib\Plugin\PluginApiRegistryInterface;
use PHPUnit\Framework\TestCase;

final class ConfigurablePluginApiRegistryTest extends TestCase
{
    /**
     * @throws DoesNotImplementHookableInterfaceException
     * @throws DoesNotImplementProviderInterfaceException
     */
    public function testConstruct(): void
    {
        $plugin = new ConfigurablePluginApiRegistry('test', dirname(__FILE__, 2));
        self::assertInstanceOf($plugin, PluginApiRegistryInterface::class);
    }
}
