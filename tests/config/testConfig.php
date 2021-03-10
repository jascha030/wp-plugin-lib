<?php

use Jascha030\PluginLib\Service\Hookable\HookableAfterInitInterface;
use Jascha030\PluginLib\Service\Hookable\LazyHookableInterface;
use Pimple\ServiceProviderInterface;

return [
    /**
     * Service Provider classes
     *
     * @var array|ServiceProviderInterface[]
     */
    'serviceProviders' => [],
    /**
     * Hookable classes
     *
     * @var array|HookableInterface[]|HookableAfterInitInterface[]|LazyHookableInterface[]
     */
    'hookableService'  => [],
    /**
     * Post type definitions
     *
     * @var array|string[]
     */
    'postTypes'        => [],
];
