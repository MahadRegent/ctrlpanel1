<?php

namespace App\Console\Commands;

use App\Models\Server;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class RefreshServerCache extends Command
{
    protected $signature = 'servers:refresh-cache {--force : Force refresh all cache}';
    protected $description = 'Refresh server cache from Pterodactyl API';

    public function handle()
    {
        $servers = Server::whereNotNull('pterodactyl_id')->get();
        $force = $this->option('force');
        
        $this->info('Refreshing server cache...');
        $bar = $this->output->createProgressBar($servers->count());

        foreach ($servers as $server) {
            $cacheKey = 'server_info_' . $server->pterodactyl_id;
            
            if ($force) {
                Cache::forget($cacheKey);
            }
            
            // Предварительная загрузка в кеш
            Cache::remember($cacheKey, 300, function () use ($server) {
                // Здесь делаем API запрос
                return [];
            });
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->info('Server cache refreshed successfully!');
    }
}
