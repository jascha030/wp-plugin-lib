<?php

declare(strict_types=1);

namespace Jascha030\PluginLib\Plugin;

use Exception;
use Jascha030\PluginLib\Container\Psr11FilterContainer;
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
     * @var string|null plugin file's path
     */
    private $pluginFile;

    /**
     * @var array|null defines classes containing methods that are to be hooked
     */
    private $registerClasses;

    /**
     * @var HookableManagerInterface|null
     */
    private $filterManager;

    /**
     * @param  string  $file
     * @param  array|null  $hookables
     * @param  HookableManagerInterface|null  $hookableManager
     * @throws Exception
     */
    final public function bootstrap(
        string $file,
        array $hookables = [],
        HookableManagerInterface $hookableManager = null
    ): void {
        $this->setPluginFile($file);

        $this->registerClasses = $hookables;

        if (! $hookableManager) {
            $hookableManager = $this->createDefaultManager();
        }

        $this->filterManager = $hookableManager;
    }

    /**
     * @throws Exception
     */
    final public function run(): void
    {
        if (! isset($this->file)) {
            $class = __CLASS__;
            throw new Exception(
                "Unable to initialise plugin, {$class}->file not set. Make sure to use the bootstrap method"
            );
        }

        $this->boot();
        $this->afterBoot();
    }

    public function afterBoot(): void
    {
        // This is optional but we don't want to open up the possibility to edit the boot method.
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    final public function getPluginFile(): string
    {
        return $this->pluginFile;
    }

    /**
     * Set plugin file,
     * Exception on invalid path
     *
     * @param  string  $file
     * @throws Exception
     */
    protected function setPluginFile(string $file): void
    {
        if (! is_readable($file)) {
            $class = __CLASS__;

            throw new Exception("Could not read {$class}->\$file Invalid path: \'{$file}\'");
        }

        $this->pluginFile = $file;
    }

    private function createDefaultManager(): HookableManagerInterface
    {
        $pimple = new Psr11FilterContainer(new \Pimple\Container());

        return new DeferringHookableManager($pimple);
    }

    /**
     * Add all classes with hookable methods to the container
     */
    private function boot(): void
    {
        foreach ($this->registerClasses as $class => $classArguments) {
            $this->filterManager->registerHookable($class, $classArguments);
        }
    }
}