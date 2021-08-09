<?php

namespace Jascha030\PluginLib\Plugin;

/**
 * Interface FilterManagerInterface.
 */
interface PluginApiRegistryInterface
{
    /**
     * Add action lazily.
     * Wraps hookable method call in a Closure to lazily load the actual hookable upon first call.
     */
    public function addAction(string $tag, string $class, string $method, int $prio = 10, int $arguments = 1): void;

    /**
     * Add filter lazily.
     * Wraps hookable method call in a Closure to lazily load the actual hookable upon first call.
     */
    public function addFilter(string $tag, string $class, string $method, int $prio = 10, int $arguments = 1): void;

    /**
     * Remove all methods of a hookableClass.
     */
    public function removeHookable(string $id): void;
}
