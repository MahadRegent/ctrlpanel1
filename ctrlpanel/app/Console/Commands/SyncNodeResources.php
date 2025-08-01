<?php

namespace App\Console\Commands;

use App\Classes\PterodactylClient;
use App\Models\Pterodactyl\Node;
use App\Settings\PterodactylSettings;
use Illuminate\Console\Command;
use Exception;

class SyncNodeResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nodes:sync-resources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync node resource information from Pterodactyl to improve performance';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting node resource synchronization...');

        try {
            $pteroSettings = app(PterodactylSettings::class);
            $client = new PterodactylClient($pteroSettings);
            
            $nodes = Node::all();
            $updated = 0;
            
            foreach ($nodes as $node) {
                try {
                    $this->info("Syncing node: {$node->name} (ID: {$node->id})");
                    
                    // Get fresh node data from Pterodactyl
                    $nodeData = $client->getNode($node->id);
                    
                    // Update cached resource data
                    $node->update([
                        'memory' => $nodeData['memory'],
                        'disk' => $nodeData['disk'],
                        'memory_overallocate' => $nodeData['memory_overallocate'],
                        'disk_overallocate' => $nodeData['disk_overallocate'],
                        'allocated_resources' => json_encode($nodeData['allocated_resources']),
                        'resource_updated_at' => now(),
                    ]);
                    
                    $updated++;
                    
                } catch (Exception $e) {
                    $this->error("Failed to sync node {$node->name}: " . $e->getMessage());
                    continue;
                }
            }
            
            $this->info("Resource synchronization completed. Updated {$updated} nodes.");
            return 0;
            
        } catch (Exception $e) {
            $this->error('Failed to sync node resources: ' . $e->getMessage());
            return 1;
        }
    }
}