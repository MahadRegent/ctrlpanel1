<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Проверяем, существует ли таблица, чтобы избежать ошибок при повторном запуске
        if (!Schema::hasTable('morune_settings')) {
            Schema::create('morune_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('enabled')->default(false);
                $table->string('shop_id')->nullable();
                $table->string('secret_key')->nullable();
                $table->string('test_secret_key')->nullable();
                $table->boolean('is_h2h_enabled')->default(false); // Добавляем это поле, если оно не было в предыдущей миграции
                $table->timestamps(); // Добавляет created_at и updated_at
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('morune_settings');
    }
};
