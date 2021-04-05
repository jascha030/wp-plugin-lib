<?php

namespace Jascha030\PluginLib\Plugin\Activation;

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
     * @inheritDoc
     */
    final public function register(): void
    {
        \register_activation_hook($this->pluginFilePath, [$this, 'activate']);
    }

    final protected function getPluginFilePath(): string
    {
        return $this->pluginFilePath;
    }
}
