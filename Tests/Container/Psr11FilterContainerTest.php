<?php

declare(strict_types=1);

namespace Container;

use Jascha030\PluginLib\Container\PimpleAsPsr11Trait;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class Psr11FilterContainerTest extends TestCase
{
    public function testFilterContainerCreation(): void
    {
        $container = new Container();
        self::assertInstanceOf(PimpleAsPsr11Trait::class, new PimpleAsPsr11Trait($container));
    }
}
