<?php

namespace Hookable;

use Jascha030\PluginLib\Hookable\FilterManager;
use Jascha030\PluginLib\Hookable\FilterManagerInterface;
use PHPUnit\Framework\TestCase;

class DeferringHookableManagerTest extends TestCase
{
    public function testCreateManager(): void
    {
        self::assertInstanceOf(
            FilterManager::class,
            new FilterManager(new Container(new Container()))
        );

        self::assertInstanceOf(
            FilterManagerInterface::class,
            new FilterManager(new Container(new Container()))
        );
    }
}
