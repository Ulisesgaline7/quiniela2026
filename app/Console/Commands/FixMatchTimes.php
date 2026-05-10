<?php

namespace App\Console\Commands;

use App\Models\WorldMatch;
use App\Services\FootballApiService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FixMatchTimes extends Command
{
    protected $signature   = 'matches:fix-times';
    protected $description = 'Recalculate closes_at for all matches (1 hour before kickoff, Tegucigalpa timezone)';

    public function handle(): void
    {
        $matches = WorldMatch::all();
        $count   = 0;

        foreach ($matches as $match) {
            if ($match->kickoff_at) {
                $match->closes_at = $match->kickoff_at->copy()->subHour();
                $match->save();
                $count++;
            }
        }

        $this->info("✅ Updated closes_at for {$count} matches (1 hour before kickoff).");
        $this->info('   App timezone: ' . config('app.timezone'));
        $this->info('   Current time: ' . now()->format('Y-m-d H:i:s T'));

        $next = WorldMatch::where('kickoff_at', '>', now())
            ->with(['homeTeam','awayTeam'])
            ->orderBy('kickoff_at')->take(5)->get();

        if ($next->count()) {
            $this->table(
                ['Partido','Kickoff (HN)','Cierra (HN)'],
                $next->map(fn($m) => [
                    $m->homeTeam->short_name . ' vs ' . $m->awayTeam->short_name,
                    $m->kickoff_at->format('d/m H:i'),
                    $m->closes_at->format('d/m H:i'),
                ])
            );
        }
    }
}
