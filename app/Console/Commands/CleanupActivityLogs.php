<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:cleanup {--days=30 : The number of days to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup old activity logs to save storage space';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $date = \Carbon\Carbon::now()->subDays($days);
        
        $count = \App\Models\ActivityLog::where('created_at', '<', $date)->delete();
        
        $this->info("Successfully deleted {$count} old activity logs (older than {$days} days).");
    }
}
