<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCacheFieldsToServersTable extends Migration
{
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->string('cached_location')->nullable();
            $table->string('cached_egg')->nullable(); 
            $table->string('cached_nest')->nullable();
            $table->string('cached_node')->nullable();
            $table->timestamp('cache_updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn([
                'cached_location',
                'cached_egg',
                'cached_nest', 
                'cached_node',
                'cache_updated_at'
            ]);
        });
    }
}
