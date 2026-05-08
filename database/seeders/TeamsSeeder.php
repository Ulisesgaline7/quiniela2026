<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\WcGroup;
use Illuminate\Database\Seeder;

class TeamsSeeder extends Seeder
{
    public function run(): void
    {
        // Official FIFA World Cup 2026 Groups (confirmed April 2026)
        $groups = [
            'A' => [
                ['name' => 'México',          'short' => 'MEX', 'flag' => '🇲🇽', 'conf' => 'CONCACAF', 'rank' => 16],
                ['name' => 'Sudáfrica',        'short' => 'RSA', 'flag' => '🇿🇦', 'conf' => 'CAF',      'rank' => 67],
                ['name' => 'Corea del Sur',    'short' => 'KOR', 'flag' => '🇰🇷', 'conf' => 'AFC',      'rank' => 23],
                ['name' => 'Chequia',          'short' => 'CZE', 'flag' => '🇨🇿', 'conf' => 'UEFA',     'rank' => 40],
            ],
            'B' => [
                ['name' => 'Canadá',           'short' => 'CAN', 'flag' => '🇨🇦', 'conf' => 'CONCACAF', 'rank' => 43],
                ['name' => 'Suiza',            'short' => 'SUI', 'flag' => '🇨🇭', 'conf' => 'UEFA',     'rank' => 19],
                ['name' => 'Qatar',            'short' => 'QAT', 'flag' => '🇶🇦', 'conf' => 'AFC',      'rank' => 37],
                ['name' => 'Bosnia y Herz.',   'short' => 'BIH', 'flag' => '🇧🇦', 'conf' => 'UEFA',     'rank' => 65],
            ],
            'C' => [
                ['name' => 'Brasil',           'short' => 'BRA', 'flag' => '🇧🇷', 'conf' => 'CONMEBOL', 'rank' => 6],
                ['name' => 'Marruecos',        'short' => 'MAR', 'flag' => '🇲🇦', 'conf' => 'CAF',      'rank' => 8],
                ['name' => 'Haití',            'short' => 'HAI', 'flag' => '🇭🇹', 'conf' => 'CONCACAF', 'rank' => 83],
                ['name' => 'Escocia',          'short' => 'SCO', 'flag' => '🏴󠁧󠁢󠁳󠁣󠁴󠁿', 'conf' => 'UEFA',     'rank' => 39],
            ],
            'D' => [
                ['name' => 'Estados Unidos',   'short' => 'USA', 'flag' => '🇺🇸', 'conf' => 'CONCACAF', 'rank' => 14],
                ['name' => 'Paraguay',         'short' => 'PAR', 'flag' => '🇵🇾', 'conf' => 'CONMEBOL', 'rank' => 55],
                ['name' => 'Australia',        'short' => 'AUS', 'flag' => '🇦🇺', 'conf' => 'AFC',      'rank' => 24],
                ['name' => 'Turquía',          'short' => 'TUR', 'flag' => '🇹🇷', 'conf' => 'UEFA',     'rank' => 26],
            ],
            'E' => [
                ['name' => 'Alemania',         'short' => 'GER', 'flag' => '🇩🇪', 'conf' => 'UEFA',     'rank' => 10],
                ['name' => 'Curazao',          'short' => 'CUW', 'flag' => '🇨🇼', 'conf' => 'CONCACAF', 'rank' => 82],
                ['name' => 'Costa de Marfil',  'short' => 'CIV', 'flag' => '🇨🇮', 'conf' => 'CAF',      'rank' => 48],
                ['name' => 'Ecuador',          'short' => 'ECU', 'flag' => '🇪🇨', 'conf' => 'CONMEBOL', 'rank' => 44],
            ],
            'F' => [
                ['name' => 'Países Bajos',     'short' => 'NED', 'flag' => '🇳🇱', 'conf' => 'UEFA',     'rank' => 7],
                ['name' => 'Japón',            'short' => 'JPN', 'flag' => '🇯🇵', 'conf' => 'AFC',      'rank' => 15],
                ['name' => 'Suecia',           'short' => 'SWE', 'flag' => '🇸🇪', 'conf' => 'UEFA',     'rank' => 25],
                ['name' => 'Túnez',            'short' => 'TUN', 'flag' => '🇹🇳', 'conf' => 'CAF',      'rank' => 30],
            ],
            'G' => [
                ['name' => 'Bélgica',          'short' => 'BEL', 'flag' => '🇧🇪', 'conf' => 'UEFA',     'rank' => 9],
                ['name' => 'Egipto',           'short' => 'EGY', 'flag' => '🇪🇬', 'conf' => 'CAF',      'rank' => 34],
                ['name' => 'Irán',             'short' => 'IRN', 'flag' => '🇮🇷', 'conf' => 'AFC',      'rank' => 22],
                ['name' => 'Nueva Zelanda',    'short' => 'NZL', 'flag' => '🇳🇿', 'conf' => 'OFC',      'rank' => 85],
            ],
            'H' => [
                ['name' => 'España',           'short' => 'ESP', 'flag' => '🇪🇸', 'conf' => 'UEFA',     'rank' => 2],
                ['name' => 'Cabo Verde',       'short' => 'CPV', 'flag' => '🇨🇻', 'conf' => 'CAF',      'rank' => 69],
                ['name' => 'Arabia Saudita',   'short' => 'KSA', 'flag' => '🇸🇦', 'conf' => 'AFC',      'rank' => 56],
                ['name' => 'Uruguay',          'short' => 'URU', 'flag' => '🇺🇾', 'conf' => 'CONMEBOL', 'rank' => 18],
            ],
            'I' => [
                ['name' => 'Francia',          'short' => 'FRA', 'flag' => '🇫🇷', 'conf' => 'UEFA',     'rank' => 1],
                ['name' => 'Senegal',          'short' => 'SEN', 'flag' => '🇸🇳', 'conf' => 'CAF',      'rank' => 14],
                ['name' => 'Noruega',          'short' => 'NOR', 'flag' => '🇳🇴', 'conf' => 'UEFA',     'rank' => 20],
                ['name' => 'Irak',             'short' => 'IRQ', 'flag' => '🇮🇶', 'conf' => 'AFC',      'rank' => 58],
            ],
            'J' => [
                ['name' => 'Argentina',        'short' => 'ARG', 'flag' => '🇦🇷', 'conf' => 'CONMEBOL', 'rank' => 3],
                ['name' => 'Argelia',          'short' => 'ALG', 'flag' => '🇩🇿', 'conf' => 'CAF',      'rank' => 35],
                ['name' => 'Austria',          'short' => 'AUT', 'flag' => '🇦🇹', 'conf' => 'UEFA',     'rank' => 27],
                ['name' => 'Jordania',         'short' => 'JOR', 'flag' => '🇯🇴', 'conf' => 'AFC',      'rank' => 71],
            ],
            'K' => [
                ['name' => 'Portugal',         'short' => 'POR', 'flag' => '🇵🇹', 'conf' => 'UEFA',     'rank' => 5],
                ['name' => 'Congo DR',         'short' => 'COD', 'flag' => '🇨🇩', 'conf' => 'CAF',      'rank' => 52],
                ['name' => 'Uzbekistán',       'short' => 'UZB', 'flag' => '🇺🇿', 'conf' => 'AFC',      'rank' => 63],
                ['name' => 'Colombia',         'short' => 'COL', 'flag' => '🇨🇴', 'conf' => 'CONMEBOL', 'rank' => 13],
            ],
            'L' => [
                ['name' => 'Inglaterra',       'short' => 'ENG', 'flag' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿', 'conf' => 'UEFA',     'rank' => 4],
                ['name' => 'Croacia',          'short' => 'CRO', 'flag' => '🇭🇷', 'conf' => 'UEFA',     'rank' => 11],
                ['name' => 'Ghana',            'short' => 'GHA', 'flag' => '🇬🇭', 'conf' => 'CAF',      'rank' => 74],
                ['name' => 'Panamá',           'short' => 'PAN', 'flag' => '🇵🇦', 'conf' => 'CONCACAF', 'rank' => 49],
            ],
        ];

        foreach ($groups as $groupName => $teams) {
            $group = WcGroup::firstOrCreate(
                ['name' => $groupName],
                ['label' => 'Grupo ' . $groupName]
            );

            foreach ($teams as $t) {
                Team::updateOrCreate(
                    ['short_name' => $t['short']],
                    [
                        'name'          => $t['name'],
                        'flag'          => $t['flag'],
                        'confederation' => $t['conf'],
                        'wc_group_id'   => $group->id,
                        'fifa_ranking'  => $t['rank'],
                        'api_code'      => $t['short'],
                    ]
                );
            }
        }
    }
}
