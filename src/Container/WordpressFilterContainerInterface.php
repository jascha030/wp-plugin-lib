<?php


namespace Jascha030\PluginLib\Container;


use Psr\Container\ContainerInterface;

interface WordpressFilterContainerInterface extends ContainerInterface
{
    /**
     * @param  string  $name
     * @param  mixed  $item
     */
    public function add(string $name, $item): void;
}