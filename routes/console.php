<?php

use Illuminate\Support\Facades\Schedule;

// Sync match results from WC2026 API every 5 minutes during tournament
Schedule::command('matches:sync')
    ->everyFiveMinutes()
    ->between('09:00', '23:59')  // Tegucigalpa time
    ->withoutOverlapping()
    ->runInBackground();
