<?php

namespace Jascha030\PluginLib\Plugin\Traits;

use Exception;
use Jascha030\PluginLib\Entity\Notice\AdminNotice;

/**
 * Trait DisplaysAdminNotices.
 */
trait DisplaysAdminNotices
{
    /**
     * @var AdminNotice[]|array
     */
    private array $notices = [];

    /**
     * Add notice for display in wp-admin.
     */
    final public function addNotice(string $message, int $type = AdminNotice::INFO): void
    {
        $this->notices[] = new AdminNotice($message, $type);
    }

    /**
     * Display added notices after load.
     *
     * @throws Exception
     */
    final public function displayNotices(): void
    {
        foreach ($this->notices as $notice) {
            $notice->display();
        }
    }

    final public function fromException(Exception $exception): void
    {
        $this->notices[] = new AdminNotice($exception->getMessage(), AdminNotice::ERROR, false);
    }
}
