<?php

namespace Jascha030\Tests\Container\Config;

use Jascha030\PluginLib\Container\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    final public function testConstruct(): void
    {
        $config = new Config('TestPlugin', __FILE__);
    }
}
