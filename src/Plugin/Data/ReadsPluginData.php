<?php

declare(strict_types=1);

namespace Jascha030\PluginLib\Plugin\Data;

trait ReadsPluginData
{
    private $pluginData = [];

    /**
     * Get data from the Plugin header by key
     *
     * @param  string  $key
     * @return null|string
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

    private function fetchPluginData(): void
    {
        $this->pluginData = get_plugin_data($this->getPluginFile(), false);
    }
}