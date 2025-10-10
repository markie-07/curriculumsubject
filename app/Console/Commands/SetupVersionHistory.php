<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class SetupVersionHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'curriculum:setup-version-history {--seed : Also seed sample data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up curriculum version history system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up Curriculum Version History System...');
        $this->newLine();

        // Check if migration is needed
        if (!Schema::hasTable('curriculum_histories')) {
            $this->info('Running curriculum_histories migration...');
            Artisan::call('migrate', [
                '--path' => 'database/migrations/2024_01_01_000000_create_curriculum_histories_table.php'
            ]);
            $this->info('✓ Migration completed');
        } else {
            $this->info('✓ curriculum_histories table already exists');
        }

        // Seed sample data if requested
        if ($this->option('seed')) {
            $this->info('Seeding sample version history data...');
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\CurriculumHistorySeeder'
            ]);
            $this->info('✓ Sample data seeded');
        }

        $this->newLine();
        $this->info('🎉 Version History System Setup Complete!');
        $this->newLine();
        
        $this->info('Features available:');
        $this->line('• Click the clock icon on curriculum cards to view version history');
        $this->line('• View detailed snapshots of curriculum states');
        $this->line('• Track changes with timestamps and user information');
        $this->line('• Compare different versions (API ready)');
        
        $this->newLine();
        $this->info('API Endpoints:');
        $this->line('• GET /api/curriculum-history/{id}/versions - List versions');
        $this->line('• GET /api/curriculum-history/{id}/versions/{versionId} - Version details');
        $this->line('• POST /api/curriculum-history/{id}/snapshot - Create snapshot');
        
        return 0;
    }
}
