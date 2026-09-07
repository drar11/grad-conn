<?php

namespace App\Console\Commands;

use App\Models\Job;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

final class ExpireJobs extends Command
{
    protected $signature = 'jobs:expire';

    protected $description = 'Close job postings whose application end date has passed';

    public function handle(): int
    {
        $count = Job::query()
            ->where('is_open', true)
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', today())
            ->update(['is_open' => false]);

        $this->info("Closed {$count} expired job posting(s).");
        Cache::forget('feed.sidebar-jobs.v1');

        return self::SUCCESS;
    }
}
