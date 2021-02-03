<?php

namespace Jascha030\PluginLib\Container;

interface ExpandableInterface
{
    /**
     * @param  string  $name
     * @param  mixed  $item
     */
    public function add(string $name, $item): void;
}