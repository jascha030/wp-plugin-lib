<?php

namespace Jascha030\PluginLib\Hookable;

interface LazyHookableInterface extends HookableInterface
{
    public static function getActions(): array;

    public static function getFilters(): array;
}