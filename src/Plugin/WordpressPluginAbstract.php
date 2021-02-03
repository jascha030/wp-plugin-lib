<?php

declare(strict_types=1);

namespace Jascha030\PluginLib\Plugin;

use Exception;
use Jascha030\PluginLib\Container\InteroperablePimpleTrait;
use Jascha030\PluginLib\Hookable\FilterManagerInterface;
use Jascha030\PluginLib\Plugin\Data\ReadsPluginData;
use Psr\Container\ContainerInterface;

/**
 * Class WordpressPluginAbstract
 *
 * Plugin class interfaces with the wp plugin api.
 * Holds and initialises other hookable classes used by a plugin.
 *
 * @package Jascha030\PluginLib\Plugin
 */
abstract class WordpressPluginAbstract implements ContainerInterface
{
    use InteroperablePimpleTrait;
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
     * @var FilterManagerInterface|null
     */
    private $filterManager;

    /**
     * @param  string  $file
     * @param  array|null  $hookables
     * @param  FilterManagerInterface|null  $hookableManager
     * @throws Exception
     */
    final public function bootstrap(
        string $file,
        array $hookables = [],
        FilterManagerInterface $hookableManager = null
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
        if (! isset($this->pluginFile)) {
            $class = __CLASS__;
            throw new \RuntimeException(
                "Unable to initialise plugin, {$class}->file not set. Make sure to use the bootstrap method"
            );
        }

        $this->boot();
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
    final public function setPluginFile(string $file): void
    {
        if (! is_readable($file)) {
            $class = __CLASS__;

            throw new \RuntimeException("Could not read {$class}->\$file Invalid path: \'{$file}\'");
        }

        $this->pluginFile = $file;
    }

    /**
     * Add all classes with hookable methods to the container
     */
    private function boot(): void
    {
        foreach ($this->registerClasses as $binding => $class) {
            if (is_int($binding)) {
                $binding = $class;
            }

            $this->filterManager->registerHookable($binding, $class);
        }
    }
}