<?php

/** @noinspection PhpUndefinedClassInspection */

namespace Jascha030\PluginLib\Entity\Post;

use WP_Post;

/**
 * Generic Post class
 *
 * @package Jascha030\PluginLib\Entity\Post
 */
class Post extends PostAbstract
{
    /**
     * @inheritDoc
     */
    final public function getPostTypeSlug(): string
    {
        return $this->getWPPost()->post_type ?? 'post';
    }

    /**
     * @inheritDoc
     */
    final public function getPostTypeClass(): ?PostTypeInterface
    {
        return null;
    }
}
