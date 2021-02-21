<?php

namespace Jascha030\PluginLib\Service\Provider;

use Jascha030\PluginLib\Entity\Post\PostType;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class WordpressProvider
 * @package Jascha030\PluginLib\Service\Provider
 */
final class WordpressProvider implements ServiceProviderInterface
{
    /**
     * @var string
     */
    private string $pluginFile;

    /**
     * WordpressProvider constructor.
     *
     * @param  string  $file
     */
    public function __construct(string $file)
    {
        $this->pluginFile = $file;
    }

    /**
     * @inheritDoc
     */
    public function register(Container $pimple): void
    {
        global $wpdb, $wp_filter;

        $pimple['wp.wpdb']        = $wpdb;
        $pimple['wp.wp_filter']   = $wp_filter;
        $pimple['plugin.file']    = $this->pluginFile;
        $pimple['plugin.root']    = dirname($this->pluginFile);
        $pimple['post.post_type'] = $pimple->factory(function (Container $container) {
            return new PostType();
        });
    }
}
