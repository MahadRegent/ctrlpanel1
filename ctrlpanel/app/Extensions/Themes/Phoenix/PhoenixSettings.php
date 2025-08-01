<?php

namespace App\Extensions\Themes\Phoenix;

use Spatie\LaravelSettings\Settings;

class PhoenixSettings extends Settings
{
    public const VERSION = '2.0.2';

    public ?string $force_theme_mode;
    public string $default_theme;
    public bool $show_theme_name;

    // Colors
    public string $primary_700;
    public string $primary_600;
    public string $primary_500;
    public string $primary_400;
    public string $primary_300;
    public string $primary_200;
    public string $primary_100;

    public string $gray_900;
    public string $gray_800;
    public string $gray_700;
    public string $gray_600;
    public string $gray_500;
    public string $gray_400;
    public string $gray_300;
    public string $gray_200;
    public string $gray_100;
    public string $gray_50;

    public static function group(): string
    {
        return 'phoenix';
    }

    public static function getOptionInputData()
    {
        return [
            'category_icon' => 'fas fa-paint-roller',
            'force_theme_mode' => [
                'label' => 'Force Theme Mode',
                'description' => 'Forces the selected mode to be applied to the dashboard regarless of user\'s settings, and disables dark / light mode switch',
                'type' => 'select',
                'options' => [
                    null => 'Disabled',
                    'dark' => 'Dark',
                    'light' => 'Light',
                ],
            ],
            'default_theme' => [
                'label' => 'Default Theme',
                'description' => 'Default theme to apply when user doesn\'t have a theme set locally. `system` makes the dashboard use user\'s system defined theme',
                'type' => 'select',
                'options' => [
                    'system' => 'System',
                    'dark' => 'Dark',
                    'light' => 'Light',
                ],
            ],
            'show_theme_name' => [
                'type' => 'boolean',
                'label' => 'Show "Phoenix Theme" in Footer',
            ],
            'primary_100' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Primary 100',
                'description' => 'Used for buttons, links, and other main elements.',
                'options' => [
                    'default' => '#edebfe',
                ],
            ],
            'primary_200' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Primary 200',
                'options' => [
                    'default' => '#ddd6fe',
                ],
            ],
            'primary_300' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Primary 300',
                'options' => [
                    'default' => '#cabffd',
                ],
            ],
            'primary_400' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Primary 400',
                'options' => [
                    'default' => '#ac94fa',
                ],
            ],
            'primary_500' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Primary 500',
                'options' => [
                    'default' => '#9061f9',
                ],
            ],
            'primary_600' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Primary 600',
                'options' => [
                    'default' => '#7e3af2',
                ],
            ],
            'primary_700' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Primary 700',
                'options' => [
                    'default' => '#6c2bd9',
                ],
            ],
            'gray_50' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Gray 50',
                'description' => 'Used for backgrounds, cards and text.',
                'options' => [
                    'default' => '#f9fafb',
                ],
            ],
            'gray_100' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Gray 100',
                'options' => [
                    'default' => '#f4f5f7',
                ],
            ],
            'gray_200' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Gray 200',
                'options' => [
                    'default' => '#e5e7eb',
                ],
            ],
            'gray_300' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Gray 300',
                'options' => [
                    'default' => '#d5d6d7',
                ],
            ],
            'gray_400' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Gray 400',
                'options' => [
                    'default' => '#c6c6c6',
                ],
            ],
            'gray_500' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Gray 500',
                'options' => [
                    'default' => '#707275',
                ],
            ],
            'gray_600' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Gray 600',
                'options' => [
                    'default' => '#4c4f52',
                ],
            ],
            'gray_700' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Gray 700',
                'options' => [
                    'default' => '#24262d',
                ],
            ],
            'gray_800' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Gray 800',
                'options' => [
                    'default' => '#1a1c23',
                ],
            ],
            'gray_900' => [
                'type' => 'string',
                'identifier' => 'color',
                'label' => 'Gray 900',
                'options' => [
                    'default' => '#121317',
                ],
            ],
        ];
    }
}
