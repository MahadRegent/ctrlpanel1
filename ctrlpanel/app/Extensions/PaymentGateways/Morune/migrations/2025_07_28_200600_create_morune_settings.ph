<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateMoruneSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('morune.shop_id', '7abcfdf7-c0d9-4ff6-885a-fbd27340f211');
        $this->migrator->addEncrypted('morune.secret_key', '34357e03c072fbafe20bf92a2f5acba336f8270d');
        $this->migrator->addEncrypted('morune.test_secret_key', null);
        $this->migrator->add('morune.enabled', false);
    }

    public function down(): void
    {
        $this->migrator->delete('morune.shop_id');
        $this->migrator->delete('morune.secret_key');
        $this->migrator->delete('morune.test_secret_key');
        $this->migrator->delete('morune.enabled');
    }
}
