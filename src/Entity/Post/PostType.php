<?php

namespace Jascha030\PluginLib\Entity\Post;

/**
 * Class PostType.
 */
class PostType extends PostTypeAbstract
{
    private ?string $slug;

    private ?string $singular;

    private ?string $plural;

    private array $arguments;

    /**
     * PostType constructor.
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
     * {@inheritDoc}
     */
    final public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param null|string $slug
     */
    final public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * {@inheritDoc}
     */
    final public function getSingular(): string
    {
        return $this->singular;
    }

    /**
     * @param null|string $singular
     */
    final public function setSingular(string $singular): void
    {
        $this->singular = $singular;
    }

    /**
     * {@inheritDoc}
     */
    final public function getPlural(): string
    {
        return $this->plural;
    }

    /**
     * @param null|string $plural
     */
    final public function setPlural(string $plural): void
    {
        $this->plural = $plural;
    }

    /**
     * {@inheritDoc}
     */
    final public function getArguments(): array
    {
        return $this->arguments;
    }

    final public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }
}
