<?php

namespace Hookable;

use Jascha030\PluginLib\Container\Psr11FilterContainer;
use Jascha030\PluginLib\Hookable\DeferringHookableManager;
use Jascha030\PluginLib\Hookable\HookableManagerInterface;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class DeferringHookableManagerTest extends TestCase
{
    public function testCreateManager(): void
    {
        self::assertInstanceOf(
            DeferringHookableManager::class,
            new DeferringHookableManager(new Psr11FilterContainer(new Container()))
        );

        self::assertInstanceOf(
            HookableManagerInterface::class,
            new DeferringHookableManager(new Psr11FilterContainer(new Container()))
        );
    }
}
