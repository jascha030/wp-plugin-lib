<?php

namespace Jascha030\PluginLib\Plugin\Activation;

/**
 * Interface OnActivateInterface
 * @package Jascha030\PluginLib\Plugin\Activation
 */
interface OnActivateInterface
{
    /**
     * Register activation hooks
     */
    public function register(): void;

    /**
     * Execute activation hooks
     */
    public function activate(): void;

    /**
     * Execute deactivation hooks
     */
    public function deactivate(): void;
}
