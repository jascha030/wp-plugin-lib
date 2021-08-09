<?php

namespace Jascha030\PluginLib\Service\Hookable;

/**
 * Interface LazyHookableInterface.
 */
interface LazyHookableInterface extends HookableInterface
{
    /**
     * Return the predefined actions.
     */
    public static function getActions(): array;

    /**
     * Return the predefined filters.
     */
    public static function getFilters(): array;
}
