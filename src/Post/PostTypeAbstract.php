<?php

namespace Jascha030\PluginLib\Post;

use Jascha030\PluginLib\Hookable\HookableAbstract;
use RuntimeException;
use WP_Query;

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
     * Register post type
     *
     * @return void
     */
    final public function register(): void
    {
        register_post_type($this->getSlug(), $this->getPostTypeConfig());
    }

    /**
     * Custom post type slug.
     *
     * @return string
     */
    abstract public function getSlug(): string;

    /**
     * Default values can be overridden by implementing `$this->overwriteConfig()`.
     *
     * @return array
     */
    private function getPostTypeConfig(): array
    {
        return array_merge(
            [
                'labels'             => $this->getLabels(),
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => [
                    'title',
                    'editor',
                    'author',
                    'thumbnail',
                    'excerpt'
                ],
            ],
            $this->customArguments()
        );
    }

    /**
     * Builds wp-admin labels.
     *
     * @return array
     */
    private function getLabels(): array
    {
        $singular            = strtolower($this->getSingularName());
        $plural              = strtolower($this->getPluralName());
        $singularCapitalised = ucfirst($singular);
        $pluralCapitalised   = ucfirst($plural);

        return [
            'name'               => _x($singularCapitalised, 'Post type general name'),
            'singular_name'      => _x($singularCapitalised, 'Post type singular name'),
            'menu_name'          => _x($pluralCapitalised, 'Admin Menu text'),
            'name_admin_bar'     => _x($singularCapitalised, 'Add New on Toolbar'),
            'add_new'            => __("Nieuw {$singular}"),
            'add_new_item'       => __("Nieuw {$singular} toevoegen"),
            'new_item'           => __("Nieuw {$singular}"),
            'edit_item'          => __("{$singularCapitalised} bewerken"),
            'view_item'          => __("Bekijk {$plural}"),
            'all_items'          => __("Alle {$plural}"),
            'search_items'       => __("{$plural} zoeken"),
            'not_found'          => __("Geen {$plural} gevonden."),
            'not_found_in_trash' => __("Geen {$plural} gevonden in prullenbak."),
        ];
    }

    /**
     * Singular name for the 'labels' setting.
     *
     * @return string
     */
    abstract public function getSingularName(): string;

    /**
     * Plural name for the 'labels' setting.
     *
     * @return string
     */
    abstract public function getPluralName(): string;

    /**
     * Override this method to add custom config options.
     *
     * @return array|null
     */
    protected function customArguments(): ?array
    {
        return [];
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

    /**
     * Post type specific wrapper for WP_Query
     *
     * @param  array  $arguments
     *
     * @return array
     */
    public function query(array $arguments = []): array
    {
        $args = [
            'post_type'      => $this->getSlug(),
            'posts_per_page' => -1,
            'post_status'    => 'publish'
        ];

        if (isset($arguments['post_type'])) {
            unset($arguments['post_type']);
        }

        return (new \WP_Query(array_merge($args, $arguments)))->get_posts();
    }

    public function create(array $postData, array $postMeta): int
    {
        $id = \wp_insert_post(
            array_merge(
                $postData,
                [
                    'post_type'   => 'uwv_company',
                    'post_status' => 'publish',
                    'post_date'   => date('Y-m-d H:i:s'),
                    'post_author' => 1
                ]
            ),
            true
        );

        if (is_wp_error($id)) {
            throw new RuntimeException($id->get_error_message());
        }

        foreach ($postMeta as $key => $val) {
            \update_post_meta($id, $key, $val);
        }

        return $id;
    }
}
