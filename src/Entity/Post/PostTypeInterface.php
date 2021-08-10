<?php

namespace Jascha030\PluginLib\Entity\Post;

/**
 * Class PostTypeAbstract.
 */
interface PostTypeInterface
{
    /**
     * Register post type.
     */
    public function register(): void;

    /**
     * Custom post type slug.
     */
    public function getSlug(): string;

    /**
     * Singular name for the 'labels' setting.
     */
    public function getSingular(): string;

    /**
     * Plural name for the 'labels' setting.
     */
    public function getPlural(): string;

    /**
     * Overrides default values.
     */
    public function getArguments(): array;

    /**
     * Post type specific wrapper for WP_Query.
     */
    public function query(array $arguments = []): array;

    /**
     * Create a post.
     */
    public function create(array $postData, array $postMeta): int;

    /**
     * @return string[]
     */
    public function getSupports(): array;
}
