<?php

declare(strict_types=1);

namespace Jascha030\PluginLib\Plugin;

use Pimple\Psr11\Container as Psr11Container;

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
    private $container;

    private $hookableServices;

    private $filterReference;

    /**
     * WordpressPluginAbstract constructor.
     *
     * @param  Psr11Container  $container
     */
    public function __construct(Psr11Container $container, array $hookableClasses)
    {
        $this->container = $container;

        $this->hookableServices = $hookableClasses;
    }

    private function hookServicesLazily()
    {
//        foreach ()
    }
}
