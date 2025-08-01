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
        // Проверяем, существуют ли колонки перед попыткой их удаления
        if (Schema::hasColumn('morune_settings', 'success_url')) {
            Schema::table('morune_settings', function (Blueprint $table) {
                $table->dropColumn('success_url');
            });
        }
        if (Schema::hasColumn('morune_settings', 'fail_url')) {
            Schema::table('morune_settings', function (Blueprint $table) {
                $table->dropColumn('fail_url');
            });
        }
        if (Schema::hasColumn('morune_settings', 'hook_url')) {
            Schema::table('morune_settings', function (Blueprint $table) {
                $table->dropColumn('hook_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     * (Откат этой миграции добавит колонки обратно, если это необходимо)
     */
    public function down(): void
    {
        Schema::table('morune_settings', function (Blueprint $table) {
            $table->string('success_url')->nullable();
            $table->string('fail_url')->nullable();
            $table->string('hook_url')->nullable();
        });
    }
};
