<?php

declare(strict_types=1);

namespace Jascha030\PluginLib\Plugin;

use Jascha030\PluginLib\Hookable\DeferringHookableManager;
use Jascha030\PluginLib\Hookable\HookableManagerInterface;
use Jascha030\PluginLib\Plugin\Data\ReadsPluginData;

/**
 * Class WordpressPluginAbstract
 *
 * Plugin class interfaces with the wp plugin api.
 * Holds and initialises other hookable classes used by a plugin.
 *
 * @package Jascha030\PluginLib\Plugin
 */
abstract class WordpressPluginAbstract
{
    use ReadsPluginData;

    /**
     * @var array defines classes containing methods that are to be hooked
     */
    private $registerClasses;

    /**
     * @var HookableManagerInterface
     */
    private $container;

    /**
     * WordpressPluginAbstract constructor.
     *
     * @param  array  $registerClasses
     * @param  HookableManagerInterface|null  $hookableManager
     */
    public function __construct(array $registerClasses = [], HookableManagerInterface $hookableManager = null)
    {
        $this->registerClasses = $registerClasses;

        $this->hookableManager = $hookableManager ?? new DeferringHookableManager();
    }

    final public function run(): void
    {
        $this->boot();
        $this->afterBoot();
    }

    /**
     * Add all classes with hookable methods to the container
     */
    private function boot(): void
    {
        foreach ($this->registerClasses as $class => $classArguments) {
            $this->hookableManager->registerHookable($class, $classArguments);
        }
    }

    public function afterBoot(): void
    {
        // This is optional but we don't want to open up the possibility to edit the boot method.
    }
}