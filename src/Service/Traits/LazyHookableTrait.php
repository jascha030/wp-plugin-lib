<?php

namespace Jascha030\PluginLib\Service\Traits;

/**
 * Class LazyHookableTrait
 *
 * @property  $actions array
 * @property  $filters array
 *
 * @package Jascha030\PluginLib\Service\Traits
 */
class LazyHookableTrait
{
    /**
     * @return array
     */
    final public static function getActions(): array
    {
        return static::$actions ?? [];
    }

    /**
     * @return array
     */
    final public static function getFilters(): array
    {
        return static::$filters ?? [];
    }
}
