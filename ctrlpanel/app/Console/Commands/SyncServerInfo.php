<?php

namespace App\Console\Commands;

use App\Classes\PterodactylClient;
use App\Models\Server;
use App\Settings\PterodactylSettings;
use Illuminate\Console\Command;
use Exception;

class SyncServerInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servers:sync-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync server information from Pterodactyl to improve performance';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting server information synchronization...');

        try {
            $pteroSettings = app(PterodactylSettings::class);
            $client = new PterodactylClient($pteroSettings);
            
            $servers = Server::all();
            $updated = 0;
            
            foreach ($servers as $server) {
                try {
                    $this->info("Syncing server: {$server->name} (ID: {$server->id})");
                    
                    // Get fresh server data from Pterodactyl
                    $serverInfo = $client->getServerAttributes($server->pterodactyl_id);
                    if (!$serverInfo) {
                        $this->warn("Server {$server->name} not found on Pterodactyl");
                        continue;
                    }
                    
                    if (!isset($serverInfo['relationships'])) {
                        continue;
                    }

                    $relationships = $serverInfo['relationships'];
                    $locationAttrs = $relationships['location']['attributes'] ?? [];
                    $eggAttrs = $relationships['egg']['attributes'] ?? [];
                    $nestAttrs = $relationships['nest']['attributes'] ?? [];
                    $nodeAttrs = $relationships['node']['attributes'] ?? [];

                    // Update cached server info
                    $updateData = [
                        'cached_location' => $locationAttrs['long'] ?? $locationAttrs['short'] ?? null,
                        'cached_egg' => $eggAttrs['name'] ?? null,
                        'cached_nest' => $nestAttrs['name'] ?? null,
                        'cached_node' => $nodeAttrs['name'] ?? null,
                        'cache_updated_at' => now(),
                    ];

                    if (isset($serverInfo['name']) && $server->name !== $serverInfo['name']) {
                        $updateData['name'] = $serverInfo['name'];
                    }

                    $server->update($updateData);
                    $updated++;
                    
                } catch (Exception $e) {
                    $this->error("Failed to sync server {$server->name}: " . $e->getMessage());
                    continue;
                }
            }
            
            $this->info("Server information synchronization completed. Updated {$updated} servers.");
            return 0;
            
        } catch (Exception $e) {
            $this->error('Failed to sync server information: ' . $e->getMessage());
            return 1;
        }
    }
}