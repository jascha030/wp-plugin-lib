<?php

namespace Jascha030\PluginLib\Plugin;

/**
 * Interface FilterManagerInterface
 *
 * @package Jascha030\PluginLib\Plugin
 */
interface FilterManagerInterface
{
    /**
     * Add action lazily.
     * Wraps hookable method call in a Closure to lazily load the actual hookable upon first call.
     *
     * @param  string  $tag
     * @param  string  $class
     * @param  string  $method
     * @param  int     $prio
     * @param  int     $arguments
     */
    public function addAction(string $tag, string $class, string $method, int $prio = 10, int $arguments = 1): void;

    /**
     * Add filter lazily.
     * Wraps hookable method call in a Closure to lazily load the actual hookable upon first call.
     *
     * @param  string  $tag
     * @param  string  $class
     * @param  string  $method
     * @param  int     $prio
     * @param  int     $arguments
     */
    public function addFilter(string $tag, string $class, string $method, int $prio = 10, int $arguments = 1): void;

    /**
     * Remove all methods of a hookableClass
     *
     * @param  string  $id
     *
     * @return void
     */
    public function removeHookable(string $id): void;
}
