<?php

declare(strict_types=1);

namespace Container;

use Jascha030\PluginLib\Container\InteroperablePimpleTrait;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class Psr11FilterContainerTest extends TestCase
{
    public function testFilterContainerCreation(): void
    {
        $container = new Container();
        self::assertInstanceOf(InteroperablePimpleTrait::class, new InteroperablePimpleTrait($container));
    }
}
