<?php

namespace Jascha030\PluginLib\Entity\Notice;

use Exception;

/**
 * Class AdminNotice.
 */
class AdminNotice
{
    /**
     * Keys indicating different types of notices
     * CSS classes are added based on these notice types.
     */
    public const ERROR   = 0;
    public const WARNING = 1;
    public const SUCCESS = 2;
    public const INFO    = 3;
    public const TYPES   = [
        self::ERROR   => 'notice-error',
        self::WARNING => 'notice-warning',
        self::SUCCESS => 'notice-success',
        self::INFO    => 'notice-info',
    ];
    /**
     * String template for admin notices.
     */
    private const HTML_TEMPLATE = '<div class="%1$s"><p>%2$s</p></div>';
    /**
     * String template injected with css class.
     */
    private const CSS_TEMPLATE = 'notice %s %s';

    private string $message;

    private string $type;

    private bool $dismissible;

    /**
     * AdminNotice constructor.
     */
    public function __construct(string $message, int $type = self::INFO, bool $dismissible = true)
    {
        $this->message = $message;

        $this->type = $this->getNoticeTypeCssClass($type);

        $this->dismissible = $dismissible;
    }

    /**
     * Print admin notice.
     *
     * @throws Exception
     */
    final public function display(): void
    {
        if (!is_admin()) {
            throw new \RuntimeException('AdminNotices should only be used in the /wp-admin section.');
        }

        echo sprintf(
            self::HTML_TEMPLATE,
            $this->getNoticeTypeCssClass($this->type),
            esc_html(__($this->message))
        );
    }

    /**
     * Validate and add notice type's CSS class
     * Defaults to info when invalid classes are provided.
     */
    private function getNoticeTypeCssClass(int $type = self::INFO): string
    {
        // Defaults to type info when invalid classes are provided
        $type = \array_key_exists($type, self::TYPES)
            ? $type
            : self::INFO;

        return sprintf(
            self::CSS_TEMPLATE,
            self::TYPES[$type],
            $this->dismissible
                ? 'is-dismissible'
                : ''
        );
    }
}
