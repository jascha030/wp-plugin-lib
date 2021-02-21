<?php

namespace Jascha030\PluginLib\Entity\Notice;

use Exception;

/**
 * Class AdminNotice
 *
 * @package Jascha030\PluginLib\Notice
 */
class AdminNotice
{
    /**
     * Keys indicating different types of notices
     *
     * adds notice-error class
     */
    public const ERROR = 0;
    /**
     * adds notice-warning class
     */
    public const WARNING = 1;
    /**
     * adds notice-success class
     */
    public const SUCCESS = 2;
    /**
     * adds notice-info class
     */
    public const INFO = 3;
    /**
     * Notice severity types and their according CSS classnames
     */
    public const TYPES = [
        self::ERROR => 'notice-error', self::WARNING => 'notice-warning', self::SUCCESS => 'notice-success',
        self::INFO  => 'notice-info'
    ];
    /**
     * String template for admin notices
     */
    private const HTML_TEMPLATE = '<div class="%1$s"><p>%2$s</p></div>';
    /**
     * String template injected with css class
     */
    private const CSS_TEMPLATE = 'notice %s %s';

    /**
     * @var string
     */
    private string $message;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var bool
     */
    private bool $dismissible;

    /**
     * AdminNotice constructor.
     *
     * @param  string  $message
     * @param  int     $type
     * @param  bool    $dismissible
     */
    public function __construct(string $message, int $type = self::INFO, bool $dismissible = true)
    {
        $this->message = $message;

        $this->type = $this->getNoticeTypeCssClass($type);

        $this->dismissible = $dismissible;
    }

    /**
     * Print admin notice
     *
     * @return void
     * @throws Exception
     */
    final public function display(): void
    {
        if (! is_admin()) {
            throw new \RuntimeException('AdminNotices should only be used in the /wp-admin section.');
        }

        echo sprintf(self::HTML_TEMPLATE,
            $this->getNoticeTypeCssClass($this->type),
            esc_html(__($this->message)));
    }

    /**
     * Validate and add notice type's CSS class
     * Defaults to info when invalid classes are provided.
     *
     * @param  int  $type
     *
     * @return string
     */
    private function getNoticeTypeCssClass(int $type = self::INFO): string
    {
        // Defaults to type info when invalid classes are provided
        $type = array_key_exists($type, self::TYPES)
            ? $type
            : self::INFO;

        return sprintf(self::CSS_TEMPLATE,
            self::TYPES[$type],
            $this->dismissible
                ? 'is-dismissible'
                : '');
    }
}
