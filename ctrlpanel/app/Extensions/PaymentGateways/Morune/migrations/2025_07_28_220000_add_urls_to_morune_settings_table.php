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
        Schema::table('morune_settings', function (Blueprint $table) {
            $table->string('success_url')->nullable();
            $table->string('fail_url')->nullable();
            $table->string('hook_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('morune_settings', function (Blueprint $table) {
            $table->dropColumn('success_url');
            $table->dropColumn('fail_url');
            $table->dropColumn('hook_url');
        });
    }
};

