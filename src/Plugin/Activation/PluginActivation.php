<?php

namespace Jascha030\PluginLib\Plugin\Activation;

use Closure;

class PluginActivation extends ActivationHandlerAbstract
{
    private string $pluginFilePath;

    private array $posts;

    private bool $flush = false;

    /**
     * Create an instance and apply filters added through the `static::add()` method
     *
     * @param string $pluginFilePath
     *
     * @return OnActivateInterface
     */
    public static function activation(string $pluginFilePath): OnActivateInterface
    {
        $activation = new static($pluginFilePath);
        $activation = apply_filters(self::sanitizePluginPath($pluginFilePath) . '_activation_filters', $activation);

        return $activation;
    }

    /**
     * Add filter callbacks to be applied to the PluginActivation object
     *
     * @param string  $pluginFilePath
     * @param Closure $closure
     */
    final public static function addActivationFilter(string $pluginFilePath, Closure $closure): void
    {
        \add_filter(self::sanitizePluginPath($pluginFilePath) . '_activation_filters', $closure);
    }

    /**
     * Add action callbacks to be executed on activation
     *
     * @param string  $pluginFilePath
     * @param Closure $closure
     */
    final public static function addActivationAction(string $pluginFilePath, Closure $closure): void
    {
        \add_action(self::sanitizePluginPath($pluginFilePath) . '_activation_actions', $closure);
    }

    private static function sanitizePluginPath(string $pluginFilePath): string
    {
        return str_replace('/', '_', $pluginFilePath);
    }

    /**
     * Define wether rewrite rules need to be flushed after activation
     */
    final public function flushAfterActivation(): void
    {
        $this->flush = true;
    }

    /**
     * @inheritDoc
     */
    public function activate(): void
    {
        do_action(self::sanitizePluginPath($this->getPluginFilePath() . '_activation_actions'));

        if ($this->flush) {
            \flush_rewrite_rules();
        }
    }

    /**
     * @inheritDoc
     */
    public function deactivate(): void
    {
        if ($this->flush) {
            \flush_rewrite_rules();
        }
    }
}
