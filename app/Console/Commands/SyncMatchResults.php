<?php

namespace App\Console\Commands;

use App\Services\FootballApiService;
use Illuminate\Console\Command;

class SyncMatchResults extends Command
{
    protected $signature   = 'quiniela:sync';
    protected $description = 'Sync match results from the Football API';

    public function handle(FootballApiService $api): int
    {
        $this->info('Syncing match results from Football API...');

        $result = $api->syncMatches();

        $this->info($result['message']);

        return self::SUCCESS;
    }
}
