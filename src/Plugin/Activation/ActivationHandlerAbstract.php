<?php

namespace Jascha030\PluginLib\Plugin\Activation;

abstract class ActivationHandlerAbstract implements OnActivateInterface
{
    private string $pluginFilePath;

    public function __construct(string $pluginFilePath)
    {
        $this->pluginFilePath = $pluginFilePath;
    }

    public static function activation(string $pluginFilePath, string $pluginPrefix, array $config = []): void
    {
        $activation = new static($pluginFilePath);
        $activation = apply_filters($pluginPrefix . '_activation_handlers', $activation);

        $activation->register();
    }

    final public function register(): void
    {
        \register_activation_hook($this->pluginFilePath, [$this, 'activate']);
    }
}
