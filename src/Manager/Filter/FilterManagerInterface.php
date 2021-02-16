<?php

namespace Jascha030\PluginLib\Manager\Filter;

use Closure;

interface FilterManagerInterface
{
    public function addAction(
        string $tag,
        string $class,
        string $method,
        int $priority = 10,
        int $arguments = 1
    ): void;

    public function addFilter(
        string $tag,
        string $class,
        string $method,
        int $priority = 10,
        int $arguments = 1
    ): void;

    /**
     * Register a hookable class and hook all it's methods
     *
     * @param  string  $alias
     * @param  mixed|string|Closure|null  $calls
     */
    public function registerHookable(string $alias, $calls = null): void;

    /**
     * Remove all methods of a hookableClass
     *
     * @param  string  $alias
     * @return bool
     */
    public function removeHookable(string $alias): bool;
}