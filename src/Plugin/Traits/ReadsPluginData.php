<?php

declare(strict_types=1);

namespace Jascha030\PluginLib\Plugin\Traits;

use Exception;
use function get_plugin_data;

/**
 * Trait ReadsPluginData.
 */
trait ReadsPluginData
{
    private ?string $pluginFile;

    private array $pluginData = [];

    /**
     * Get data from the Plugin header by key.
     *
     * @throws Exception
     */
    final public function getPluginData(string $key): ?string
    {
        if (!isset($this->pluginData[$key])) {
            $this->fetchPluginData();
        }

        return $this->pluginData[$key] ?? null;
    }

    abstract public function getPluginFile(): string;

    /**
     * @throws Exception
     */
    private function fetchPluginData(): void
    {
        if (!\defined('ABSPATH')) {
            throw new \RuntimeException('Couldn\'t get Plugin data, ABSPATH not defined, make sure you are using this in an active Wordpress install');
        }

        $path = ABSPATH.'wp-admin/includes/plugin.php';

        if (!\function_exists('get_plugin_data') || is_readable($path)) {
            require_once $path;
        }

        $this->pluginData = get_plugin_data($this->getPluginFile(), false);
    }
}
