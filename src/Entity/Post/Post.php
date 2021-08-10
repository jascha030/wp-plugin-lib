<?php

/** @noinspection PhpUndefinedClassInspection */

namespace Jascha030\PluginLib\Entity\Post;

/**
 * Generic Post class.
 */
class Post extends PostAbstract
{
    /**
     * {@inheritDoc}
     */
    final public function getPostTypeSlug(): string
    {
        return $this->getWPPost()->post_type ?? 'post';
    }

    /**
     * {@inheritDoc}
     */
    final public function getPostTypeClass(): ?PostTypeInterface
    {
        return null;
    }
}
