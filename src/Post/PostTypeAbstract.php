<?php

namespace Jascha030\PluginLib\Post;

use Jascha030\PluginLib\Hookable\HookableAbstract;

use function add_action;
use function register_post_type;

/**
 * Class PostTypeAbstract
 *
 * @package Jascha030\PluginLib\Post
 */
abstract class PostTypeAbstract extends HookableAbstract
{
    /**
     * @var string The post type slug
     */
    protected $slug;

    /**
     * @var array Arguments for register_post_type method
     */
    protected $arguments;

    /**
     * Register post type
     *
     * @return void
     */
    final public function register(): void
    {
        register_post_type($this->slug, $this->arguments);
    }

    /**
     * Hook register_post_type upon construction
     *
     * @return void
     */
    public function hookMethods(): void
    {
        add_action('init', [$this, 'register']);
    }
}
