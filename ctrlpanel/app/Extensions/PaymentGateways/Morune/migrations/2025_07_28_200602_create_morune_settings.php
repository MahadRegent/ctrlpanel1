<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMoruneSettings extends Migration
{
    public function up()
    {
        $existing = DB::table('settings')->where('group', 'morune')->count();
        if ($existing === 0) {
            DB::table('settings')->insert([
                ['group' => 'morune','name' => 'shop_id','payload' => json_encode('7abcfdf7-c0d9-4ff6-885a-fbd27340f211'),'created_at' => now(),'updated_at' => now()],
                ['group' => 'morune','name' => 'secret_key','payload' => json_encode('34357e03c072fbafe20bf92a2f5acba336f8270d'),'created_at' => now(),'updated_at' => now()],
                ['group' => 'morune','name' => 'test_secret_key','payload' => json_encode(null),'created_at' => now(),'updated_at' => now()],
                ['group' => 'morune','name' => 'enabled','payload' => json_encode(false),'created_at' => now(),'updated_at' => now()],
            ]);
        }
    }

    public function down()
    {
        DB::table('settings')->where('group', 'morune')->delete();
    }
}
