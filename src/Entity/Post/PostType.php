<?php

namespace Jascha030\PluginLib\Entity\Post;

/**
 * Class PostType
 * @package Jascha030\PluginLib\Entity\Post
 */
class PostType extends PostTypeAbstract
{
    /**
     * @var string|null
     */
    private ?string $slug;

    /**
     * @var string|null
     */
    private ?string $singular;

    /**
     * @var string|null
     */
    private ?string $plural;

    /**
     * @var array
     */
    private array $arguments;

    /**
     * PostType constructor.
     *
     * @param  string|null  $slug
     * @param  string|null  $singular
     * @param  string|null  $plural
     *
     * @param  array  $arguments
     */
    public function __construct(
        ?string $slug = null,
        ?string $singular = null,
        ?string $plural = null,
        array $arguments = []
    ) {
        $this->slug      = $slug;
        $this->singular  = $singular;
        $this->plural    = $plural;
        $this->arguments = $arguments;
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param  string|null  $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @inheritDoc
     */
    public function getSingular(): string
    {
        return $this->singular;
    }

    /**
     * @param  string|null  $singular
     */
    public function setSingular(string $singular): void
    {
        $this->singular = $singular;
    }

    /**
     * @inheritDoc
     */
    public function getPlural(): string
    {
        return $this->plural;
    }

    /**
     * @param  string|null  $plural
     */
    public function setPlural(string $plural): void
    {
        $this->plural = $plural;
    }

    /**
     * @inheritDoc
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param  array  $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }
}
