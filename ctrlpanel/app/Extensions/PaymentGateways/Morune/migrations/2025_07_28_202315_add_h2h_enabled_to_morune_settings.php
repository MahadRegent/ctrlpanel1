<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddH2hEnabledToMoruneSettings extends Migration
{
    public function up()
    {
        $existing = DB::table('settings')->where('group', 'morune')->where('name', 'is_h2h_enabled')->count();
        if ($existing === 0) {
            DB::table('settings')->insert([
                'group' => 'morune',
                'name' => 'is_h2h_enabled',
                'payload' => json_encode(false),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        DB::table('settings')->where('group', 'morune')->where('name', 'is_h2h_enabled')->delete();
    }
}
