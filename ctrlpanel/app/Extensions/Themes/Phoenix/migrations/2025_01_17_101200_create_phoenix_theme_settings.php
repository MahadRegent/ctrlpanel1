<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->migrator->add('phoenix.force_theme_mode', null);
        $this->migrator->add('phoenix.default_theme', 'system');
        $this->migrator->add('phoenix.show_theme_name', true);

        $this->migrator->add('phoenix.primary_100', '#edebfe');
        $this->migrator->add('phoenix.primary_200', '#ddd6fe');
        $this->migrator->add('phoenix.primary_300', '#cabffd');
        $this->migrator->add('phoenix.primary_400', '#ac94fa');
        $this->migrator->add('phoenix.primary_500', '#9061f9');
        $this->migrator->add('phoenix.primary_600', '#7e3af2');
        $this->migrator->add('phoenix.primary_700', '#6c2bd9');

        $this->migrator->add('phoenix.gray_50', '#f9fafb');
        $this->migrator->add('phoenix.gray_100', '#f4f5f7');
        $this->migrator->add('phoenix.gray_200', '#e5e7eb');
        $this->migrator->add('phoenix.gray_300', '#d5d6d7');
        $this->migrator->add('phoenix.gray_400', '#c6c6c6');
        $this->migrator->add('phoenix.gray_500', '#707275');
        $this->migrator->add('phoenix.gray_600', '#4c4f52');
        $this->migrator->add('phoenix.gray_700', '#24262d');
        $this->migrator->add('phoenix.gray_800', '#1a1c23');
        $this->migrator->add('phoenix.gray_900', '#121317');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->migrator->delete('phoenix.force_theme_mode');
        $this->migrator->delete('phoenix.default_theme');
        $this->migrator->delete('phoenix.show_theme_name');

        $this->migrator->delete('phoenix.primary_100');
        $this->migrator->delete('phoenix.primary_200');
        $this->migrator->delete('phoenix.primary_300');
        $this->migrator->delete('phoenix.primary_400');
        $this->migrator->delete('phoenix.primary_500');
        $this->migrator->delete('phoenix.primary_600');
        $this->migrator->delete('phoenix.primary_700');

        $this->migrator->delete('phoenix.gray_50');
        $this->migrator->delete('phoenix.gray_100');
        $this->migrator->delete('phoenix.gray_200');
        $this->migrator->delete('phoenix.gray_300');
        $this->migrator->delete('phoenix.gray_400');
        $this->migrator->delete('phoenix.gray_500');
        $this->migrator->delete('phoenix.gray_600');
        $this->migrator->delete('phoenix.gray_700');
        $this->migrator->delete('phoenix.gray_800');
        $this->migrator->delete('phoenix.gray_900');
    }
};
