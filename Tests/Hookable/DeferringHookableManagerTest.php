<?php

namespace Hookable;

use Jascha030\PluginLib\Container\InteroperablePimpleTrait;
use Jascha030\PluginLib\Hookable\FilterManager;
use Jascha030\PluginLib\Hookable\FilterManagerInterface;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class DeferringHookableManagerTest extends TestCase
{
    public function testCreateManager(): void
    {
        self::assertInstanceOf(
            FilterManager::class,
            new FilterManager(new InteroperablePimpleTrait(new Container()))
        );

        self::assertInstanceOf(
            FilterManagerInterface::class,
            new FilterManager(new InteroperablePimpleTrait(new Container()))
        );
    }
}
