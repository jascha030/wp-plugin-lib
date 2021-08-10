<?php

namespace Jascha030\PluginLib\Service\Provider;

use Jascha030\PluginLib\Entity\Post\PostType;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class WordpressProvider.
 */
final class WordpressProvider implements ServiceProviderInterface
{
    private string $pluginFile;

    /**
     * @var null|string
     */
    private string $prefix;

    /**
     * WordpressProvider constructor.
     */
    public function __construct(string $prefix, string $file)
    {
        $this->prefix     = $prefix;
        $this->pluginFile = $file;
    }

    /**
     * {@inheritDoc}
     */
    public function register(Container $pimple): void
    {
        global $wpdb, $wp_filter;

        $pimple['wp.wpdb']      = $wpdb;
        $pimple['wp.wp_filter'] = $wp_filter;
        $pimple['wp.post_type'] = $pimple->factory(function (Container $container) {
            return new PostType();
        });

        $pimple['plugin.file'] = $this->pluginFile;
        $pimple['plugin.root'] = \dirname($this->pluginFile);
    }
}
