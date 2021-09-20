<?php

namespace Jascha030\PluginLib\Service\Settings;

use Jascha030\PluginLib\Service\Hookable\LazyHookableInterface;
use Jascha030\PluginLib\Service\Traits\LazyHookableTrait;

class SettingsSubMenu implements LazyHookableInterface
{
    use LazyHookableTrait;

    public static array $actions = [
        'admin_menu' => 'page',
        'admin_init' => 'init',
    ];

    private string $id;

    /**
     * @var array{title: string, description:string, type:string}
     */
    private array $options;

    private string $parent;

    private string $title;

    /**
     * @param string $id      slug of the settings page
     * @param array  $options array of wp_options/settings
     */
    public function __construct(string $id, string $parent, string $title, array $options = [])
    {
        $this->id      = $id;
        $this->parent  = $parent;
        $this->title   = $title;
        $this->options = $options;
    }

    public function page(): void
    {
        add_submenu_page(
            $this->parent,
            $this->title,
            $this->title,
            'manage_options',
            $this->id,
            [$this, 'render']
        );
    }

    public function init(): void
    {
        add_settings_section(
            $this->id.'_section_api',
            __('API', $this->id),
            function (array $arguments) {
                echo sprintf(
                    '<p id="%s">%s</p>',
                    esc_attr($arguments['id']),
                    esc_html_e('API Related settings', $this->id)
                );
            },
            $this->id
        );

        foreach ($this->options as $key => $value) {
            // e.g. if $key = 'user', $option_id = 'carerix_user'.
            $option_id = $this->id.'_'.$key;

            register_setting($this->id, $option_id);

            add_settings_field(
                $option_id,
                __($value['title'], $this->id),
                function () use ($option_id, $value) {
                    $this->renderField($option_id, $value);
                },
                $this->id,
                $this->id.'_section_api',
            );
        }
    }

    public function renderField(string $id, array $arguments): void
    {
        $option = get_option($id);

        echo sprintf(
            '<input class="regular-text" type="%s" name="%s" value="%s" /><br/>',
            $arguments['type'] ?? 'text',
            $id,
            $option
        );

        if (isset($arguments['description'])) {
            echo sprintf('<p class="description">%s</p>', esc_html_e($arguments['description'], $this->id));
        }
    }

    public function render(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (isset($_GET['settings-updated'])) {
            add_settings_error($this->id.'_messages', $this->id.'_message', __('Settings Saved', $this->id), 'updated');
        }

        settings_errors($this->id.'_messages');

        echo sprintf(
            '<h1>%s</h1><div class="wrap"><form action="options.php" method="post">',
            ucfirst(esc_html(get_admin_page_title()))
        );

        settings_fields($this->id);
        do_settings_sections($this->id);
        submit_button('Save Settings');

        echo '</form></div>';
    }
}
