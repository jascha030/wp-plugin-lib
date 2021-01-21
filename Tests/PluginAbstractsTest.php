<?php

declare(strict_types=1);

use Jascha030\PluginLib\Plugin\WordpressPluginAbstract;
use PHPUnit\Framework\TestCase;

/**
 * Class PluginAbstractsTest
 */
class PluginAbstractsTest extends TestCase
{
    protected $anonymousPluginClass;

    public function testAbstractClassMethod(): void
    {
        self::assertInstanceOf(
            WordpressPluginAbstract::class,
            $this->anonymousPluginClass->getThis()
        );
    }

    protected function setUp(): void
    {
        $this->anonymousPluginClass = new class extends WordpressPluginAbstract {
            public function getThis(): self
            {
                return $this;
            }
        };
    }
}
