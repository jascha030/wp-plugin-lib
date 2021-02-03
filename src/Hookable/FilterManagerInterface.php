<?php


namespace Jascha030\PluginLib\Hookable;

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
     * @param  string  $binding
     * @param  mixed|string|Closure|null  $calls
     */
    public function registerHookable(string $binding, $calls = null): void;
}