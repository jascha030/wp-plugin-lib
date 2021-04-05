<?php

namespace Jascha030\PluginLib\Plugin\Activation;

use Closure;
use Jascha030\PluginLib\Entity\Post\PostInterface;
use Jascha030\PluginLib\Plugin\Traits\DisplaysAdminNotices;

/**
 * Class ActivationHandlerAbstract
 * @package Jascha030\PluginLib\Plugin\Activation
 */
abstract class ActivationHandlerAbstract implements OnActivateInterface
{
    use DisplaysAdminNotices;

    private array $postsToInsert;

    private string $pluginFilePath;

    private bool $flush = false;

    public function __construct(string $pluginFilePath)
    {
        $this->pluginFilePath = $pluginFilePath;

        $this->postsToInsert = [];
    }

    /**
     * Create an instance and apply filters added through the `static::add()` method
     *
     * @param string $pluginFilePath
     *
     * @return OnActivateInterface
     */
    public static function activation(string $pluginFilePath): OnActivateInterface
    {
        $activation = new static($pluginFilePath);
        $activation = apply_filters(self::sanitizePluginPath($pluginFilePath) . '_activation_filters', $activation);

        return $activation;
    }

    /**
     * Add filter callbacks to be applied to the PluginActivation object
     *
     * @param string  $pluginFilePath
     * @param Closure $closure
     */
    final public static function addActivationFilter(string $pluginFilePath, Closure $closure): void
    {
        \add_filter(self::sanitizePluginPath($pluginFilePath) . '_activation_filters', $closure);
    }

    /**
     * Add action callbacks to be executed on activation
     *
     * @param string  $pluginFilePath
     * @param Closure $closure
     */
    final public static function addActivationAction(string $pluginFilePath, Closure $closure): void
    {
        \add_action(self::sanitizePluginPath($pluginFilePath) . '_activation_actions', $closure);
    }

    private static function sanitizePluginPath(string $pluginFilePath): string
    {
        return str_replace('/', '_', $pluginFilePath);
    }

    /**
     * @inheritDoc
     */
    final public function register(): void
    {
        \register_activation_hook($this->pluginFilePath, [$this, 'activate']);
    }

    /**
     * @param array $posts
     *
     * @return OnActivateInterface
     */
    final public function createOnActivation(array $posts): OnActivateInterface
    {
        foreach ($posts as $post) {
            if (! is_array($post) && ! is_subclass_of($post, PostInterface::class)) {
                continue;
            }

            $this->postsToInsert[] = $post;
        }

        return $this;
    }

    /**
     * Define wether rewrite rules need to be flushed after activation
     */
    final public function flushAfterActivation(): void
    {
        $this->flush = true;
    }

    final protected function getPluginFilePath(): string
    {
        return $this->pluginFilePath;
    }

    /**
     * Even when you don't need to, why wouldn't you?
     * It's common courtesy.
     */
    private function flush(): void
    {
        if ($this->flush) {
            \flush_rewrite_rules();
        }
    }
}
