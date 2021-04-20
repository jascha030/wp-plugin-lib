<?php

use Jascha030\PluginLib\Service\Hookable\HookableAfterInitInterface;
use Jascha030\PluginLib\Service\Hookable\HookableInterface;
use Jascha030\PluginLib\Service\Hookable\LazyHookableInterface;
use Pimple\ServiceProviderInterface;

return [
    'name' => 'TestPlugin',
    /**
     * @var array|ServiceProviderInterface[]
     */
    'serviceProviders' => [],
    /**
     * @var array|HookableInterface[]|HookableAfterInitInterface[]|LazyHookableInterface[]
     */
    'hookableServices' => [],
    /**
     * @var array|string[]
     */
    'postTypes' => [],
];
