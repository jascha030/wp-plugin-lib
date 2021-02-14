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
     * @param  string  $alias
     * @param  mixed|string|Closure|null  $calls
     */
    public function registerHookable(string $alias, $calls = null): void;

    /**
     * @param  string  $alias
     * @return bool
     */
//    Todo: the whole point here (sort of).
//    public function removeHookable(string $alias): bool;
}