<?php

namespace Jascha030\PluginLib\Service\Hookable;

/**
 * Interface LazyHookableInterface
 * @package Jascha030\PluginLib\Service\Hookable
 */
interface LazyHookableInterface extends HookableInterface
{
    /**
     * Return the predefined actions
     * @return array
     */
    public static function getActions(): array;

    /**
     * Return the predefined filters
     * @return array
     */
    public static function getFilters(): array;
}
