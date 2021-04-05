<?php

namespace Jascha030\PluginLib\Plugin\Activation;

use Closure;

/**
 * Class ActivationHandlerAbstract
 * @package Jascha030\PluginLib\Plugin\Activation
 */
abstract class ActivationHandlerAbstract implements OnActivateInterface
{
    private string $pluginFilePath;

    public function __construct(string $pluginFilePath)
    {
        $this->pluginFilePath = $pluginFilePath;
    }

    /**
     * Create an instance and apply filters added through the `static::add()` method
     *
     * @param string $pluginFilePath
     * @param array  $config
     */
    public static function activation(string $pluginFilePath, array $config = []): void
    {
        $activation = new static($pluginFilePath);
        $activation = apply_filters(self::sanitizePluginPath($pluginFilePath) . '_activation_handlers', $activation);

        $activation->register();
    }

    /**
     * Add callbacks on activation
     *
     * @param string  $pluginFilePath
     * @param Closure $closure
     */
    final public static function add(string $pluginFilePath, Closure $closure): void
    {
        add_filter(self::sanitizePluginPath($pluginFilePath) . '_activation_handlers', $closure);
    }

    private static function sanitizePluginPath(string $pluginFilePath): string
    {
        return str_replace('/', '_', $pluginFilePath);
    }

    /**
     * @inheritDoc
     */
    final public function register(): void
    {
        \register_activation_hook($this->pluginFilePath, [$this, 'activate']);
    }
}
