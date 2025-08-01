<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->bigInteger('memory')->nullable()->after('description');
            $table->bigInteger('disk')->nullable()->after('memory');
            $table->integer('memory_overallocate')->default(0)->after('disk');
            $table->integer('disk_overallocate')->default(0)->after('memory_overallocate');
            $table->json('allocated_resources')->nullable()->after('disk_overallocate');
            $table->timestamp('resource_updated_at')->nullable()->after('allocated_resources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn([
                'memory',
                'disk', 
                'memory_overallocate',
                'disk_overallocate',
                'allocated_resources',
                'resource_updated_at'
            ]);
        });
    }
};