<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearDashboardCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear dashboard statistics cache for performance optimization';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            Cache::forget('dashboard_stats');
            $this->info('Dashboard cache cleared successfully!');
            
            // Also clear other related caches if they exist
            $cacheKeys = [
                'dashboard_stats',
                'curriculum_stats',
                'user_stats',
                'activity_stats'
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            
            $this->info('All dashboard-related caches cleared.');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error clearing dashboard cache: ' . $e->getMessage());
            return 1;
        }
    }
}
