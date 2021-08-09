<?php

/** @noinspection PhpUndefinedClassInspection */

namespace Jascha030\PluginLib\Entity\Post;

use WP_Post;

/**
 * Interface PostInterface.
 */
interface PostInterface
{
    /**
     * Return a new clean instance.
     */
    public static function new(): self;

    /**
     * Retrieve a post as WP_post object.
     */
    public static function find(int $id): ?self;

    /**
     * Check if given id resolves to an existing WP_Post.
     */
    public static function exists(int $id): bool;

    /**
     * Create post object to insert directly.
     */
    public static function create(array $data, array $meta = []): ?self;

    public function getPostData(): array;

    /**
     * Insert's current post in the database
     * Can be either to create or update.
     */
    public function insert(): ?self;

    public function getMeta(string $key, bool $single = true);

    /**
     * Create or update meta.
     *
     * @param mixed $value
     */
    public function setMeta(string $key, $value): bool;

    /**
     * Get post type.
     */
    public function getPostTypeSlug(): string;

    /**
     * Get post type class when it exists.
     */
    public function getPostTypeClass(): ?PostTypeInterface;

    public function getWPPost(): ?WP_Post;
}
