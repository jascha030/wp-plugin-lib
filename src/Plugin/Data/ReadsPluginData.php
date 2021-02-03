<?php

declare(strict_types=1);

namespace Jascha030\PluginLib\Plugin\Data;

use Exception;

trait ReadsPluginData
{
    private $pluginData = [];

    /**
     * Get data from the Plugin header by key
     *
     * @param  string  $key
     * @return null|string
     * @throws Exception
     */
    final public function getPluginData(string $key): ?string
    {
        if (! isset($this->pluginData[$key])) {
            $this->fetchPluginData();
        }

        return $this->pluginData[$key] ?? null;
    }

    /**
     * Get main plugin file's path
     *
     * @return string
     */
    abstract public function getPluginFile(): string;

    /**
     * @throws Exception
     */
    private function fetchPluginData(): void
    {
        if (! defined('ABSPATH')) {
            throw new Exception(
                'Couldn\'t get Plugin data, ABSPATH not defined, make sure you are using this in an active Wordpress install'
            );
        }

        if (! function_exists('get_plugin_data')) {
            require_once ABSPATH.'wp-admin/includes/plugin.php';
        }

        $this->pluginData = \get_plugin_data($this->getPluginFile(), false);
    }
}