<?php

namespace Hookable;

use Jascha030\PluginLib\Container\PimpleAsPsr11Trait;
use Jascha030\PluginLib\Hookable\DeferringFilterManager;
use Jascha030\PluginLib\Hookable\FilterManagerInterface;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class DeferringHookableManagerTest extends TestCase
{
    public function testCreateManager(): void
    {
        self::assertInstanceOf(
            DeferringFilterManager::class,
            new DeferringFilterManager(new PimpleAsPsr11Trait(new Container()))
        );

        self::assertInstanceOf(
            FilterManagerInterface::class,
            new DeferringFilterManager(new PimpleAsPsr11Trait(new Container()))
        );
    }
}
