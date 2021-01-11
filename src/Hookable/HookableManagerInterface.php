<?php


namespace Jascha030\PluginLib\Hookable;

use Psr\Container\ContainerInterface;

interface HookableManagerInterface extends ContainerInterface
{
    public function __construct(\Jascha030\PluginLib\Container\WordpressFilterContainerInterface $container);

    public function addAction(
        string $tag,
        string $class,
        string $method,
        int $priority = 10,
        int $acceptedArguments = 1
    ): void;

    public function addFilter(
        string $tag,
        string $class,
        string $method,
        int $priority = 10,
        int $acceptedArguments = 1
    ): void;

    public function registerHookable(string $className, array $classArguments): void;
}