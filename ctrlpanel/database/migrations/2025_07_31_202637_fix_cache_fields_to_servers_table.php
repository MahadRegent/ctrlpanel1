<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            if (!Schema::hasColumn('servers', 'cached_location')) {
                $table->string('cached_location')->nullable();
            }
            if (!Schema::hasColumn('servers', 'cached_egg')) {
                $table->string('cached_egg')->nullable();
            }
            if (!Schema::hasColumn('servers', 'cached_nest')) {
                $table->string('cached_nest')->nullable();
            }
            if (!Schema::hasColumn('servers', 'cached_node')) {
                $table->string('cached_node')->nullable();
            }
            if (!Schema::hasColumn('servers', 'cache_updated_at')) {
                $table->timestamp('cache_updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $columns = ['cached_location', 'cached_egg', 'cached_nest', 'cached_node', 'cache_updated_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('servers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
