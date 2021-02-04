<?php

declare(strict_types=1);

namespace Container;

use Jascha030\PluginLib\Container\Container;
use PHPUnit\Framework\TestCase;

class Psr11FilterContainerTest extends TestCase
{
    public function testFilterContainerCreation(): void
    {
        $container = new Container();
        self::assertInstanceOf(Container::class, new Container($container));
    }
}
