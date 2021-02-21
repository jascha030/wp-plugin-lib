<?php

namespace Jascha030\Tests\Container;

use Jascha030\PluginLib\Container\Config\Config;
use Jascha030\PluginLib\Container\ContainerBuilder;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementHookableInterfaceException;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementProviderInterfaceException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class ContainerBuilderTest
 * @package Jascha030\Tests\Container
 */
class ContainerBuilderTest extends TestCase
{
    /**
     * @throws DoesNotImplementHookableInterfaceException
     * @throws DoesNotImplementProviderInterfaceException
     */
    final public function testInvokeMethod(): void
    {
        $config    = new Config();
        $builder   = new ContainerBuilder();
        $container = $builder($config);

        self::assertTrue(is_subclass_of($container, ContainerInterface::class));
    }
}
