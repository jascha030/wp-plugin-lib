<?php

declare(strict_types=1);

namespace Jascha030\PluginLib\Plugin;

/**
 * Class WordpressPluginAbstract
 *
 * Plugin class interfaces with the wp plugin api.
 * Holds and initialises other hookable classes used by a plugin.
 *
 * @package Jascha030\PluginLib\Plugin\Traits
 */
abstract class WordpressPluginAbstract
{
    private array $hookableServices = [];

    private array $filterReference = [];

    public function __construct()
    {
    }
}
