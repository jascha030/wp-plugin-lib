<?php

declare(strict_types=1);

namespace Jascha030\Tests\Container;

use Jascha030\PluginLib\Container\Config\Config;
use Jascha030\PluginLib\Container\ContainerBuilder;
use Jascha030\PluginLib\Entity\Post\PostType;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class ContainerBuilderTest.
 *
 * @internal
 * @coversNothing
 */
class ContainerBuilderTest extends TestCase
{
    final public function testInvokeMethod(): void
    {
        $config    = new Config('Test Plugin', __FILE__);
        $builder   = new ContainerBuilder();
        $container = $builder($config);

        self::assertTrue(is_subclass_of($container, ContainerInterface::class));
        self::assertSame($container->get('plugin.file'), __FILE__);
        self::assertInstanceOf(PostType::class, $container->get('wp.post_type'));
    }
}
