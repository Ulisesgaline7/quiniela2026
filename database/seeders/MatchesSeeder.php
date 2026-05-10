<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\WorldMatch;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MatchesSeeder extends Seeder
{
    public function run(): void
    {
        $t = fn(string $short) => Team::where('short_name', $short)->firstOrFail()->id;

        // Official FIFA World Cup 2026 Group Stage Fixtures
        // Source: FIFA official schedule (all times ET, converted to UTC-5)
        $matches = [
            // ── GRUPO A ──
            ['home'=>'MEX','away'=>'RSA','group'=>'A','day'=>1,'kickoff'=>'2026-06-11 15:00','venue'=>'Estadio Azteca','city'=>'Ciudad de México'],
            ['home'=>'KOR','away'=>'CZE','group'=>'A','day'=>1,'kickoff'=>'2026-06-12 12:00','venue'=>'SoFi Stadium','city'=>'Los Ángeles'],
            ['home'=>'MEX','away'=>'KOR','group'=>'A','day'=>2,'kickoff'=>'2026-06-18 18:00','venue'=>'Estadio Akron','city'=>'Guadalajara'],
            ['home'=>'RSA','away'=>'CZE','group'=>'A','day'=>2,'kickoff'=>'2026-06-18 15:00','venue'=>'AT&T Stadium','city'=>'Dallas'],
            ['home'=>'MEX','away'=>'CZE','group'=>'A','day'=>3,'kickoff'=>'2026-06-24 20:00','venue'=>'Estadio Azteca','city'=>'Ciudad de México'],
            ['home'=>'KOR','away'=>'RSA','group'=>'A','day'=>3,'kickoff'=>'2026-06-24 20:00','venue'=>'Levi\'s Stadium','city'=>'San Francisco'],
            // ── GRUPO B ──
            ['home'=>'CAN','away'=>'BIH','group'=>'B','day'=>1,'kickoff'=>'2026-06-12 18:00','venue'=>'BMO Field','city'=>'Toronto'],
            ['home'=>'SUI','away'=>'QAT','group'=>'B','day'=>1,'kickoff'=>'2026-06-13 12:00','venue'=>'BC Place','city'=>'Vancouver'],
            ['home'=>'CAN','away'=>'SUI','group'=>'B','day'=>2,'kickoff'=>'2026-06-19 18:00','venue'=>'BC Place','city'=>'Vancouver'],
            ['home'=>'QAT','away'=>'BIH','group'=>'B','day'=>2,'kickoff'=>'2026-06-19 15:00','venue'=>'Gillette Stadium','city'=>'Boston'],
            ['home'=>'CAN','away'=>'QAT','group'=>'B','day'=>3,'kickoff'=>'2026-06-25 20:00','venue'=>'BC Place','city'=>'Vancouver'],
            ['home'=>'SUI','away'=>'BIH','group'=>'B','day'=>3,'kickoff'=>'2026-06-25 20:00','venue'=>'BMO Field','city'=>'Toronto'],
            // ── GRUPO C ──
            ['home'=>'BRA','away'=>'MAR','group'=>'C','day'=>1,'kickoff'=>'2026-06-13 18:00','venue'=>'MetLife Stadium','city'=>'Nueva York'],
            ['home'=>'SCO','away'=>'HAI','group'=>'C','day'=>1,'kickoff'=>'2026-06-13 15:00','venue'=>'Hard Rock Stadium','city'=>'Miami'],
            ['home'=>'BRA','away'=>'SCO','group'=>'C','day'=>2,'kickoff'=>'2026-06-20 18:00','venue'=>'AT&T Stadium','city'=>'Dallas'],
            ['home'=>'MAR','away'=>'HAI','group'=>'C','day'=>2,'kickoff'=>'2026-06-20 15:00','venue'=>'Estadio Azteca','city'=>'Ciudad de México'],
            ['home'=>'BRA','away'=>'HAI','group'=>'C','day'=>3,'kickoff'=>'2026-06-26 20:00','venue'=>'MetLife Stadium','city'=>'Nueva York'],
            ['home'=>'MAR','away'=>'SCO','group'=>'C','day'=>3,'kickoff'=>'2026-06-26 20:00','venue'=>'Hard Rock Stadium','city'=>'Miami'],
            // ── GRUPO D ──
            ['home'=>'USA','away'=>'PAR','group'=>'D','day'=>1,'kickoff'=>'2026-06-12 21:00','venue'=>'SoFi Stadium','city'=>'Los Ángeles'],
            ['home'=>'AUS','away'=>'TUR','group'=>'D','day'=>1,'kickoff'=>'2026-06-13 21:00','venue'=>'Levi\'s Stadium','city'=>'San Francisco'],
            ['home'=>'USA','away'=>'AUS','group'=>'D','day'=>2,'kickoff'=>'2026-06-19 21:00','venue'=>'Arrowhead Stadium','city'=>'Kansas City'],
            ['home'=>'PAR','away'=>'TUR','group'=>'D','day'=>2,'kickoff'=>'2026-06-20 21:00','venue'=>'Gillette Stadium','city'=>'Boston'],
            ['home'=>'USA','away'=>'TUR','group'=>'D','day'=>3,'kickoff'=>'2026-06-25 20:00','venue'=>'AT&T Stadium','city'=>'Dallas'],
            ['home'=>'PAR','away'=>'AUS','group'=>'D','day'=>3,'kickoff'=>'2026-06-25 20:00','venue'=>'Hard Rock Stadium','city'=>'Miami'],
            // ── GRUPO E ──
            ['home'=>'GER','away'=>'CUW','group'=>'E','day'=>1,'kickoff'=>'2026-06-14 18:00','venue'=>'NRG Stadium','city'=>'Houston'],
            ['home'=>'CIV','away'=>'ECU','group'=>'E','day'=>1,'kickoff'=>'2026-06-14 15:00','venue'=>'Estadio Akron','city'=>'Guadalajara'],
            ['home'=>'GER','away'=>'CIV','group'=>'E','day'=>2,'kickoff'=>'2026-06-20 12:00','venue'=>'Lincoln Financial Field','city'=>'Filadelfia'],
            ['home'=>'CUW','away'=>'ECU','group'=>'E','day'=>2,'kickoff'=>'2026-06-21 12:00','venue'=>'Estadio BBVA','city'=>'Monterrey'],
            ['home'=>'GER','away'=>'ECU','group'=>'E','day'=>3,'kickoff'=>'2026-06-26 20:00','venue'=>'AT&T Stadium','city'=>'Dallas'],
            ['home'=>'CUW','away'=>'CIV','group'=>'E','day'=>3,'kickoff'=>'2026-06-26 20:00','venue'=>'NRG Stadium','city'=>'Houston'],
            // ── GRUPO F ──
            ['home'=>'NED','away'=>'JPN','group'=>'F','day'=>1,'kickoff'=>'2026-06-14 21:00','venue'=>'AT&T Stadium','city'=>'Dallas'],
            ['home'=>'SWE','away'=>'TUN','group'=>'F','day'=>1,'kickoff'=>'2026-06-14 12:00','venue'=>'Estadio BBVA','city'=>'Monterrey'],
            ['home'=>'NED','away'=>'SWE','group'=>'F','day'=>2,'kickoff'=>'2026-06-20 18:00','venue'=>'NRG Stadium','city'=>'Houston'],
            ['home'=>'JPN','away'=>'TUN','group'=>'F','day'=>2,'kickoff'=>'2026-06-20 15:00','venue'=>'Estadio BBVA','city'=>'Monterrey'],
            ['home'=>'NED','away'=>'TUN','group'=>'F','day'=>3,'kickoff'=>'2026-06-27 20:00','venue'=>'AT&T Stadium','city'=>'Dallas'],
            ['home'=>'JPN','away'=>'SWE','group'=>'F','day'=>3,'kickoff'=>'2026-06-27 20:00','venue'=>'NRG Stadium','city'=>'Houston'],
            // ── GRUPO G ──
            ['home'=>'BEL','away'=>'EGY','group'=>'G','day'=>1,'kickoff'=>'2026-06-15 18:00','venue'=>'Lumen Field','city'=>'Seattle'],
            ['home'=>'IRN','away'=>'NZL','group'=>'G','day'=>1,'kickoff'=>'2026-06-15 15:00','venue'=>'SoFi Stadium','city'=>'Los Ángeles'],
            ['home'=>'BEL','away'=>'IRN','group'=>'G','day'=>2,'kickoff'=>'2026-06-21 18:00','venue'=>'SoFi Stadium','city'=>'Los Ángeles'],
            ['home'=>'EGY','away'=>'NZL','group'=>'G','day'=>2,'kickoff'=>'2026-06-21 15:00','venue'=>'Lumen Field','city'=>'Seattle'],
            ['home'=>'BEL','away'=>'NZL','group'=>'G','day'=>3,'kickoff'=>'2026-06-27 20:00','venue'=>'Lumen Field','city'=>'Seattle'],
            ['home'=>'EGY','away'=>'IRN','group'=>'G','day'=>3,'kickoff'=>'2026-06-27 20:00','venue'=>'SoFi Stadium','city'=>'Los Ángeles'],
            // ── GRUPO H ──
            ['home'=>'ESP','away'=>'CPV','group'=>'H','day'=>1,'kickoff'=>'2026-06-15 21:00','venue'=>'Mercedes-Benz Stadium','city'=>'Atlanta'],
            ['home'=>'KSA','away'=>'URU','group'=>'H','day'=>1,'kickoff'=>'2026-06-15 12:00','venue'=>'Hard Rock Stadium','city'=>'Miami'],
            ['home'=>'ESP','away'=>'KSA','group'=>'H','day'=>2,'kickoff'=>'2026-06-21 21:00','venue'=>'Lincoln Financial Field','city'=>'Filadelfia'],
            ['home'=>'CPV','away'=>'URU','group'=>'H','day'=>2,'kickoff'=>'2026-06-22 12:00','venue'=>'Arrowhead Stadium','city'=>'Kansas City'],
            ['home'=>'ESP','away'=>'URU','group'=>'H','day'=>3,'kickoff'=>'2026-06-26 20:00','venue'=>'Estadio Akron','city'=>'Guadalajara'],
            ['home'=>'CPV','away'=>'KSA','group'=>'H','day'=>3,'kickoff'=>'2026-06-26 20:00','venue'=>'Mercedes-Benz Stadium','city'=>'Atlanta'],
            // ── GRUPO I ──
            ['home'=>'FRA','away'=>'SEN','group'=>'I','day'=>1,'kickoff'=>'2026-06-16 18:00','venue'=>'MetLife Stadium','city'=>'Nueva York'],
            ['home'=>'NOR','away'=>'IRQ','group'=>'I','day'=>1,'kickoff'=>'2026-06-16 15:00','venue'=>'Gillette Stadium','city'=>'Boston'],
            ['home'=>'FRA','away'=>'NOR','group'=>'I','day'=>2,'kickoff'=>'2026-06-22 18:00','venue'=>'Lincoln Financial Field','city'=>'Filadelfia'],
            ['home'=>'SEN','away'=>'IRQ','group'=>'I','day'=>2,'kickoff'=>'2026-06-22 15:00','venue'=>'MetLife Stadium','city'=>'Nueva York'],
            ['home'=>'FRA','away'=>'IRQ','group'=>'I','day'=>3,'kickoff'=>'2026-06-27 20:00','venue'=>'MetLife Stadium','city'=>'Nueva York'],
            ['home'=>'SEN','away'=>'NOR','group'=>'I','day'=>3,'kickoff'=>'2026-06-27 20:00','venue'=>'Gillette Stadium','city'=>'Boston'],
            // ── GRUPO J ──
            ['home'=>'ARG','away'=>'ALG','group'=>'J','day'=>1,'kickoff'=>'2026-06-16 21:00','venue'=>'Arrowhead Stadium','city'=>'Kansas City'],
            ['home'=>'AUT','away'=>'JOR','group'=>'J','day'=>1,'kickoff'=>'2026-06-17 12:00','venue'=>'Lumen Field','city'=>'Seattle'],
            ['home'=>'ARG','away'=>'AUT','group'=>'J','day'=>2,'kickoff'=>'2026-06-22 21:00','venue'=>'AT&T Stadium','city'=>'Dallas'],
            ['home'=>'ALG','away'=>'JOR','group'=>'J','day'=>2,'kickoff'=>'2026-06-23 12:00','venue'=>'Arrowhead Stadium','city'=>'Kansas City'],
            ['home'=>'ARG','away'=>'JOR','group'=>'J','day'=>3,'kickoff'=>'2026-06-27 20:00','venue'=>'AT&T Stadium','city'=>'Dallas'],
            ['home'=>'ALG','away'=>'AUT','group'=>'J','day'=>3,'kickoff'=>'2026-06-27 20:00','venue'=>'Lumen Field','city'=>'Seattle'],
            // ── GRUPO K ──
            ['home'=>'POR','away'=>'COD','group'=>'K','day'=>1,'kickoff'=>'2026-06-17 18:00','venue'=>'NRG Stadium','city'=>'Houston'],
            ['home'=>'UZB','away'=>'COL','group'=>'K','day'=>1,'kickoff'=>'2026-06-17 15:00','venue'=>'Estadio Azteca','city'=>'Ciudad de México'],
            ['home'=>'POR','away'=>'UZB','group'=>'K','day'=>2,'kickoff'=>'2026-06-23 18:00','venue'=>'Estadio BBVA','city'=>'Monterrey'],
            ['home'=>'COD','away'=>'COL','group'=>'K','day'=>2,'kickoff'=>'2026-06-23 15:00','venue'=>'NRG Stadium','city'=>'Houston'],
            ['home'=>'POR','away'=>'COL','group'=>'K','day'=>3,'kickoff'=>'2026-06-27 20:00','venue'=>'Hard Rock Stadium','city'=>'Miami'],
            ['home'=>'UZB','away'=>'COD','group'=>'K','day'=>3,'kickoff'=>'2026-06-27 20:00','venue'=>'Estadio Azteca','city'=>'Ciudad de México'],
            // ── GRUPO L ──
            ['home'=>'ENG','away'=>'CRO','group'=>'L','day'=>1,'kickoff'=>'2026-06-17 21:00','venue'=>'AT&T Stadium','city'=>'Dallas'],
            ['home'=>'GHA','away'=>'PAN','group'=>'L','day'=>1,'kickoff'=>'2026-06-17 18:00','venue'=>'BMO Field','city'=>'Toronto'],
            ['home'=>'ENG','away'=>'GHA','group'=>'L','day'=>2,'kickoff'=>'2026-06-23 21:00','venue'=>'Gillette Stadium','city'=>'Boston'],
            ['home'=>'CRO','away'=>'PAN','group'=>'L','day'=>2,'kickoff'=>'2026-06-23 18:00','venue'=>'Mercedes-Benz Stadium','city'=>'Atlanta'],
            ['home'=>'ENG','away'=>'PAN','group'=>'L','day'=>3,'kickoff'=>'2026-06-27 20:00','venue'=>'AT&T Stadium','city'=>'Dallas'],
            ['home'=>'CRO','away'=>'GHA','group'=>'L','day'=>3,'kickoff'=>'2026-06-27 20:00','venue'=>'BMO Field','city'=>'Toronto'],
        ];

        foreach ($matches as $m) {
            $kickoff = Carbon::parse($m['kickoff']);
            WorldMatch::updateOrCreate(
                [
                    'home_team_id' => $t($m['home']),
                    'away_team_id' => $t($m['away']),
                    'phase'        => 'groups',
                ],
                [
                    'group_name' => $m['group'],
                    'matchday'   => $m['day'],
                    'kickoff_at' => $kickoff,
                    'closes_at'  => $kickoff->copy()->subMinutes(5),  // 5 min before kickoff
                    'venue'      => $m['venue'],
                    'city'       => $m['city'],
                    'is_open'    => true,
                    'status'     => 'scheduled',
                ]
            );
        }
    }
}
