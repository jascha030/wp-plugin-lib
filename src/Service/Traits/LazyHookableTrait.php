<?php

namespace Jascha030\PluginLib\Service\Traits;

/**
 * Class LazyHookableTrait.
 *
 * @property  $actions array
 * @property  $filters array
 */
trait LazyHookableTrait
{
    final public static function getActions(): array
    {
        return static::$actions ?? [];
    }

    final public static function getFilters(): array
    {
        return static::$filters ?? [];
    }
}
