<?php

namespace App\Extensions\PaymentGateways\Morune;

use Spatie\LaravelSettings\Settings;

class MoruneSettings extends Settings
{
    public bool $enabled = false;
    public ?string $shop_id;
    public ?string $secret_key;
    public ?string $test_secret_key;
    public bool $is_h2h_enabled = false;
    // Удалены success_url, fail_url, hook_url

    public static function group(): string
    {
        return 'morune';
    }

    public static function getOptionInputData(): array
    {
        return [
            'category_icon' => 'fas fa-dollar-sign',
            'shop_id' => [
                'type' => 'string',
                'label' => 'Shop ID',
                'description' => 'The Shop ID of your Morune account',
            ],
            'secret_key' => [
                'type' => 'string',
                'label' => 'Secret Key',
                'description' => 'The Secret Key of your Morune account',
            ],
            'test_secret_key' => [
                'type' => 'string',
                'label' => 'Test Secret Key',
                'description' => 'The Test Secret Key used when app_env = local',
            ],
            'enabled' => [
                'type' => 'boolean',
                'label' => 'Enabled',
                'description' => 'Enable this payment gateway',
            ],
            'is_h2h_enabled' => [
                'type' => 'boolean',
                'label' => 'Enable H2H Payments',
                'description' => 'Enable human-to-human (P2P) payment methods',
            ],
            // Удалены поля для success_url, fail_url, hook_url из настроек
        ];
    }
}
