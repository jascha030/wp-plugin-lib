<?php

namespace Jascha030\PluginLib\Hookable;

abstract class LazyHookableAbstract
{
    abstract public static function getActions(): array;

    abstract public static function getFilters(): array;
}