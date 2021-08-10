<?php

/** @noinspection PhpUndefinedMethodInspection */

/** @noinspection PhpUndefinedClassInspection */

namespace Jascha030\PluginLib\Entity\Post;

use Exception;
use WP_Post;

abstract class PostAbstract implements PostInterface
{
    public const POST_DATA = [
        'ID',
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_content',
        'post_title',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'post_name',
        'to_ping',
        'pinged',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count',
        'filter',
    ];

    public const REQUIRED_POST_DATA = [
        'post_title',
        'post_content',
        'post_type',
        'post_status',
        'post_author',
    ];

    private ?WP_Post $post;

    private array $postData;

    private array $postMeta;

    /**
     * PostAbstract constructor.
     *
     * @param null|int|mixed|WP_Post $post
     */
    public function __construct($post = null)
    {
        $this->post     = null;
        $this->postData = [];
        $this->postMeta = [];

        if (null !== $post) {
            $this->setPost(WP_Post::get_instance($post));
        }
    }

    /**
     * {@inheritDoc}
     */
    final public static function new(): PostInterface
    {
        return new static();
    }

    /**
     * {@inheritDoc}
     */
    public static function find(int $id): ?PostInterface
    {
        $post = WP_Post::get_instance($id);

        if (false === $post) {
            return null;
        }

        return new static($post);
    }

    /**
     * {@inheritDoc}
     */
    public static function exists(int $id): bool
    {
        // WP_Post class is final, get_post always returns WP_Post,
        // long as `get_post()` is called without 2nd argument
        return false !== WP_Post::get_instance($id);
    }

    /**
     * {@inheritDoc}
     */
    public static function create(array $data, array $meta = []): ?PostInterface
    {
        $new = (new static())->fill($data, $meta);
        $new->insert();

        return $new;
    }

    final public function fill(array $postData, array $meta = []): PostInterface
    {
        $this->setPostData($postData);

        $this->setPostMeta($meta);

        return $this;
    }

    public function getPostData(): array
    {
        if (!$this->post) {
            return $this->postData;
        }

        return $this->post->to_array();
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    final public function insert(): ?PostInterface
    {
        if (isset($this->post) && null !== $this->post) {
            $this->postData = $this->post->to_array();
        }

        $data = $this->postData;

        if (!isset($data['post_type'])) {
            $data['post_type'] = $this->getPostTypeSlug();
        }

        if (!isset($data['post_author'])) {
            $data['post_author'] = wp_get_current_user()->ID;
        }

        $data['meta_input'] = $this->postMeta;

        if (!\count(array_intersect_key(array_flip(self::REQUIRED_POST_DATA), $data))
            === \count(self::REQUIRED_POST_DATA)) {
            $requiredKeys = implode(', ', self::REQUIRED_POST_DATA);

            throw new Exception("Couldn't insert post, check required keys ({$requiredKeys})");
        }

        $id         = wp_insert_post($data);
        $this->post = null;

        $this->setPost(WP_Post::get_instance($id));

        return $id;
    }

    public function getMeta(string $key, bool $single = true)
    {
        if (!$this->post) {
            if (!\array_key_exists($key, $this->postMeta)) {
                return null;
            }

            return $this->postMeta[$key];
        }

        return get_post_meta($this->post->ID, $key, $single);
    }

    /**
     * {@inheritDoc}
     */
    public function setMeta(string $key, $value): bool
    {
        if (!$this->post) {
            $this->postMeta[$key] = $value;
        }

        if (!add_post_meta($this->post->ID, $key, $value, true)) {
            update_post_meta($this->post->ID, $key, $value);
        }
    }

    public function getWPPost(): ?WP_Post
    {
        return $this->post;
    }

    private function setPostData(array $postData): void
    {
        foreach ($postData as $key => $value) {
            if (!\in_array($key, self::POST_DATA, true)) {
                continue;
            }

            if (!$this->post) {
                $this->postData[$key] = $value;
            } else {
                $this->post->{$key} = $value;
            }
        }
    }

    private function setPost(WP_Post $post): void
    {
        $this->post = $post;
    }
}
