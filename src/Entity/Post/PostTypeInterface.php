<?php

namespace Jascha030\PluginLib\Entity\Post;

/**
 * Class PostTypeAbstract
 * @package Jascha030\PluginLib\Entity\Post
 */
interface PostTypeInterface
{
    /**
     * Register post type
     *
     * @return void
     */
    public function register(): void;

    /**
     * Custom post type slug.
     *
     * @return string
     */
    public function getSlug(): string;

    /**
     * Singular name for the 'labels' setting.
     *
     * @return string
     */
    public function getSingular(): string;

    /**
     * Plural name for the 'labels' setting.
     *
     * @return string
     */
    public function getPlural(): string;

    /**
     * Overrides default values.
     *
     * @return array
     */
    public function getArguments(): array;

    /**
     * Post type specific wrapper for WP_Query
     *
     * @param  array  $arguments
     *
     * @return array
     */
    public function query(array $arguments = []): array;

    /**
     * Create a post
     *
     * @param  array  $postData
     * @param  array  $postMeta
     *
     * @return int
     */
    public function create(array $postData, array $postMeta): int;

    /**
     * @return string[]
     */
    public function getSupports(): array;
}
