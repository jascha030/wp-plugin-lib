<?php

/** @noinspection PhpUndefinedClassInspection */

namespace Jascha030\PluginLib\Entity\Post;

use WP_Post;

/**
 * Interface PostInterface
 * @package Jascha030\PluginLib\Entity\Post
 */
interface PostInterface
{
    /**
     * Return a new clean instance
     * @return PostInterface
     */
    public static function new(): PostInterface;

    /**
     * Retrieve a post as WP_post object
     *
     * @param int $id
     *
     * @return PostInterface|null
     */
    public static function find(int $id): ?PostInterface;

    /**
     * Check if given id resolves to an existing WP_Post
     *
     * @param int $id
     *
     * @return bool
     */
    public static function exists(int $id): bool;

    /**
     * Create post object to insert directly
     *
     * @param array $data
     * @param array $meta
     *
     * @return PostInterface|null
     */
    public static function create(array $data, array $meta = []): ?PostInterface;

    public function getPostData(): array;

    /**
     * Insert's current post in the database
     * Can be either to create or update
     * @return PostInterface|null
     */
    public function insert(): ?PostInterface;

    public function getMeta(string $key, bool $single = true);

    /**
     * Create or update meta
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function setMeta(string $key, $value): bool;

    /**
     * Get post type
     * @return string
     */
    public function getPostTypeSlug(): string;

    /**
     * Get post type class when it exists
     * @return PostTypeInterface|null
     */
    public function getPostTypeClass(): ?PostTypeInterface;

    public function getWPPost(): ?WP_Post;
}
