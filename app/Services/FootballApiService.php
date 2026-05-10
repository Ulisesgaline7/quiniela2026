<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * WC2026 API Service — https://api.wc2026api.com
 * Endpoints:
 *   GET /matches          — all 104 matches with live scores
 *   GET /matches/{id}     — single match
 *   GET /groups           — 12 groups with teams
 *
 * Key: wc26_KdsoHimfzeLmwRxQsv3LzZ
 * Rate limit: 100 req/day (free tier)
 */
class FootballApiService
{
    private const BASE = 'https://api.wc2026api.com';
    private const KEY  = 'wc26_KdsoHimfzeLmwRxQsv3LzZ';

    private function headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . self::KEY,
            'Accept'        => 'application/json',
        ];
    }

    private function get(string $path, int $ttl = 60): ?array
    {
        $cacheKey = 'wc2026api_' . md5($path);

        return Cache::remember($cacheKey, $ttl, function () use ($path) {
            try {
                $response = Http::timeout(10)
                    ->withHeaders($this->headers())
                    ->get(self::BASE . $path);

                if ($response->successful()) {
                    return $response->json();
                }
                Log::warning("WC2026 API [{$path}]: HTTP " . $response->status());
            } catch (\Exception $e) {
                Log::warning("WC2026 API [{$path}]: " . $e->getMessage());
            }
            return null;
        });
    }

    // ── All matches ───────────────────────────────────────────────────
    public function getAllMatches(): array
    {
        return $this->get('/matches', 120) ?? [];
    }

    // ── Single match ──────────────────────────────────────────────────
    public function getMatch(int $apiId): ?array
    {
        return $this->get("/matches/{$apiId}", 60);
    }

    // ── Groups ────────────────────────────────────────────────────────
    public function getGroups(): array
    {
        return $this->get('/groups', 3600) ?? [];
    }

    // ── Live matches (status = live) ──────────────────────────────────
    public function getLiveMatches(): array
    {
        // Bust cache for live data
        Cache::forget('wc2026api_' . md5('/matches'));
        $all = $this->getAllMatches();
        return array_filter($all, fn($m) => ($m['status'] ?? '') === 'live');
    }

    // ── Map API round to our phase ────────────────────────────────────
    public static function roundToPhase(string $round): string
    {
        return match(strtolower($round)) {
            'group'  => 'groups',
            'r32'    => 'round_of_32',
            'r16'    => 'round_of_16',
            'qf'     => 'quarters',
            'sf'     => 'semis',
            '3rd'    => 'third_place',
            'final'  => 'final',
            default  => 'groups',
        };
    }

    // ── Team comparison (static data — API doesn't have H2H) ─────────
    public function getTeamComparison(string $team1Code, string $team2Code): array
    {
        $stats = $this->teamStats();
        return [
            'team1'  => $stats[strtoupper($team1Code)] ?? $this->defaultStats($team1Code),
            'team2'  => $stats[strtoupper($team2Code)] ?? $this->defaultStats($team2Code),
            'source' => 'static',
        ];
    }

    private function defaultStats(string $code): array
    {
        return [
            'code' => $code, 'world_cups' => 0, 'best_result' => 'Grupos',
            'titles' => 0, 'avg_goals_scored' => 1.2, 'avg_goals_conceded' => 1.1,
            'win_rate' => 33, 'form' => ['W','D','L','D','L'],
            'star_player' => '—', 'fifa_rank' => 50,
        ];
    }

    private function teamStats(): array
    {
        return [
            'ARG' => ['code'=>'ARG','world_cups'=>18,'best_result'=>'Campeón','titles'=>3,'avg_goals_scored'=>1.8,'avg_goals_conceded'=>0.9,'win_rate'=>62,'form'=>['W','W','W','D','W'],'star_player'=>'Lionel Messi','fifa_rank'=>3],
            'FRA' => ['code'=>'FRA','world_cups'=>16,'best_result'=>'Campeón','titles'=>2,'avg_goals_scored'=>1.9,'avg_goals_conceded'=>0.8,'win_rate'=>65,'form'=>['W','W','W','W','D'],'star_player'=>'Kylian Mbappé','fifa_rank'=>1],
            'BRA' => ['code'=>'BRA','world_cups'=>22,'best_result'=>'Campeón','titles'=>5,'avg_goals_scored'=>2.1,'avg_goals_conceded'=>0.7,'win_rate'=>68,'form'=>['W','D','W','W','L'],'star_player'=>'Vinicius Jr.','fifa_rank'=>6],
            'ESP' => ['code'=>'ESP','world_cups'=>16,'best_result'=>'Campeón','titles'=>1,'avg_goals_scored'=>1.7,'avg_goals_conceded'=>0.6,'win_rate'=>64,'form'=>['W','W','W','W','W'],'star_player'=>'Lamine Yamal','fifa_rank'=>2],
            'ENG' => ['code'=>'ENG','world_cups'=>16,'best_result'=>'Campeón','titles'=>1,'avg_goals_scored'=>1.6,'avg_goals_conceded'=>0.7,'win_rate'=>60,'form'=>['W','W','W','W','D'],'star_player'=>'Jude Bellingham','fifa_rank'=>4],
            'GER' => ['code'=>'GER','world_cups'=>20,'best_result'=>'Campeón','titles'=>4,'avg_goals_scored'=>1.8,'avg_goals_conceded'=>0.9,'win_rate'=>61,'form'=>['W','W','D','W','W'],'star_player'=>'Florian Wirtz','fifa_rank'=>10],
            'POR' => ['code'=>'POR','world_cups'=>8,'best_result'=>'3er lugar','titles'=>0,'avg_goals_scored'=>1.7,'avg_goals_conceded'=>0.8,'win_rate'=>58,'form'=>['W','W','W','D','W'],'star_player'=>'Cristiano Ronaldo','fifa_rank'=>5],
            'NED' => ['code'=>'NED','world_cups'=>11,'best_result'=>'Subcampeón','titles'=>0,'avg_goals_scored'=>1.6,'avg_goals_conceded'=>0.9,'win_rate'=>55,'form'=>['W','D','W','W','L'],'star_player'=>'Virgil van Dijk','fifa_rank'=>7],
            'MAR' => ['code'=>'MAR','world_cups'=>6,'best_result'=>'4to lugar','titles'=>0,'avg_goals_scored'=>1.2,'avg_goals_conceded'=>0.6,'win_rate'=>48,'form'=>['W','W','D','W','D'],'star_player'=>'Achraf Hakimi','fifa_rank'=>8],
            'BEL' => ['code'=>'BEL','world_cups'=>14,'best_result'=>'3er lugar','titles'=>0,'avg_goals_scored'=>1.7,'avg_goals_conceded'=>0.9,'win_rate'=>57,'form'=>['W','W','D','W','W'],'star_player'=>'Kevin De Bruyne','fifa_rank'=>9],
            'URU' => ['code'=>'URU','world_cups'=>14,'best_result'=>'Campeón','titles'=>2,'avg_goals_scored'=>1.4,'avg_goals_conceded'=>0.8,'win_rate'=>50,'form'=>['W','D','W','L','W'],'star_player'=>'Federico Valverde','fifa_rank'=>18],
            'COL' => ['code'=>'COL','world_cups'=>7,'best_result'=>'Cuartos','titles'=>0,'avg_goals_scored'=>1.5,'avg_goals_conceded'=>0.9,'win_rate'=>52,'form'=>['W','W','D','W','D'],'star_player'=>'James Rodríguez','fifa_rank'=>13],
            'MEX' => ['code'=>'MEX','world_cups'=>17,'best_result'=>'Cuartos','titles'=>0,'avg_goals_scored'=>1.3,'avg_goals_conceded'=>1.0,'win_rate'=>45,'form'=>['D','W','D','L','W'],'star_player'=>'Hirving Lozano','fifa_rank'=>16],
            'USA' => ['code'=>'USA','world_cups'=>11,'best_result'=>'3er lugar','titles'=>0,'avg_goals_scored'=>1.2,'avg_goals_conceded'=>1.1,'win_rate'=>42,'form'=>['L','L','D','W','D'],'star_player'=>'Christian Pulisic','fifa_rank'=>14],
            'JPN' => ['code'=>'JPN','world_cups'=>7,'best_result'=>'Cuartos','titles'=>0,'avg_goals_scored'=>1.3,'avg_goals_conceded'=>0.9,'win_rate'=>44,'form'=>['W','W','D','W','W'],'star_player'=>'Takefusa Kubo','fifa_rank'=>15],
            'SEN' => ['code'=>'SEN','world_cups'=>3,'best_result'=>'Cuartos','titles'=>0,'avg_goals_scored'=>1.3,'avg_goals_conceded'=>0.8,'win_rate'=>46,'form'=>['W','D','W','W','D'],'star_player'=>'Sadio Mané','fifa_rank'=>14],
            'NOR' => ['code'=>'NOR','world_cups'=>3,'best_result'=>'Cuartos','titles'=>0,'avg_goals_scored'=>1.8,'avg_goals_conceded'=>1.0,'win_rate'=>50,'form'=>['W','W','W','D','W'],'star_player'=>'Erling Haaland','fifa_rank'=>20],
            'CRO' => ['code'=>'CRO','world_cups'=>7,'best_result'=>'Subcampeón','titles'=>0,'avg_goals_scored'=>1.4,'avg_goals_conceded'=>0.8,'win_rate'=>52,'form'=>['D','W','D','W','D'],'star_player'=>'Luka Modrić','fifa_rank'=>11],
            'KOR' => ['code'=>'KOR','world_cups'=>11,'best_result'=>'4to lugar','titles'=>0,'avg_goals_scored'=>1.2,'avg_goals_conceded'=>1.0,'win_rate'=>40,'form'=>['W','D','L','W','D'],'star_player'=>'Son Heung-min','fifa_rank'=>23],
            'AUS' => ['code'=>'AUS','world_cups'=>6,'best_result'=>'4to lugar','titles'=>0,'avg_goals_scored'=>1.1,'avg_goals_conceded'=>1.1,'win_rate'=>38,'form'=>['D','W','L','D','W'],'star_player'=>'Mat Ryan','fifa_rank'=>24],
            'SUI' => ['code'=>'SUI','world_cups'=>12,'best_result'=>'Cuartos','titles'=>0,'avg_goals_scored'=>1.3,'avg_goals_conceded'=>0.8,'win_rate'=>46,'form'=>['W','D','W','D','W'],'star_player'=>'Granit Xhaka','fifa_rank'=>19],
            'ECU' => ['code'=>'ECU','world_cups'=>4,'best_result'=>'Octavos','titles'=>0,'avg_goals_scored'=>1.2,'avg_goals_conceded'=>1.0,'win_rate'=>40,'form'=>['D','W','D','L','W'],'star_player'=>'Moisés Caicedo','fifa_rank'=>44],
            'RSA' => ['code'=>'RSA','world_cups'=>3,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>0.9,'avg_goals_conceded'=>1.2,'win_rate'=>30,'form'=>['L','D','W','L','D'],'star_player'=>'Percy Tau','fifa_rank'=>67],
            'CAN' => ['code'=>'CAN','world_cups'=>2,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>1.1,'avg_goals_conceded'=>1.1,'win_rate'=>38,'form'=>['W','D','W','D','L'],'star_player'=>'Alphonso Davies','fifa_rank'=>43],
            'PAR' => ['code'=>'PAR','world_cups'=>9,'best_result'=>'Cuartos','titles'=>0,'avg_goals_scored'=>1.0,'avg_goals_conceded'=>1.0,'win_rate'=>35,'form'=>['D','L','W','D','L'],'star_player'=>'Miguel Almirón','fifa_rank'=>55],
            'TUR' => ['code'=>'TUR','world_cups'=>3,'best_result'=>'3er lugar','titles'=>0,'avg_goals_scored'=>1.4,'avg_goals_conceded'=>1.1,'win_rate'=>44,'form'=>['W','W','D','W','L'],'star_player'=>'Arda Güler','fifa_rank'=>26],
            'CZE' => ['code'=>'CZE','world_cups'=>9,'best_result'=>'Subcampeón','titles'=>0,'avg_goals_scored'=>1.2,'avg_goals_conceded'=>1.0,'win_rate'=>40,'form'=>['W','D','L','W','D'],'star_player'=>'Tomáš Souček','fifa_rank'=>40],
            'QAT' => ['code'=>'QAT','world_cups'=>2,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>0.8,'avg_goals_conceded'=>1.5,'win_rate'=>25,'form'=>['L','L','D','L','W'],'star_player'=>'Akram Afif','fifa_rank'=>37],
            'SCO' => ['code'=>'SCO','world_cups'=>8,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>1.1,'avg_goals_conceded'=>1.1,'win_rate'=>38,'form'=>['D','W','L','D','W'],'star_player'=>'Scott McTominay','fifa_rank'=>39],
            'HAI' => ['code'=>'HAI','world_cups'=>1,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>0.7,'avg_goals_conceded'=>1.4,'win_rate'=>20,'form'=>['L','D','L','W','L'],'star_player'=>'Frantzdy Pierrot','fifa_rank'=>83],
            'IRQ' => ['code'=>'IRQ','world_cups'=>2,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>1.0,'avg_goals_conceded'=>1.2,'win_rate'=>32,'form'=>['W','D','L','W','D'],'star_player'=>'Aymen Hussein','fifa_rank'=>58],
            'ALG' => ['code'=>'ALG','world_cups'=>4,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>1.1,'avg_goals_conceded'=>0.9,'win_rate'=>40,'form'=>['W','D','W','L','D'],'star_player'=>'Riyad Mahrez','fifa_rank'=>35],
            'AUT' => ['code'=>'AUT','world_cups'=>7,'best_result'=>'3er lugar','titles'=>0,'avg_goals_scored'=>1.3,'avg_goals_conceded'=>1.0,'win_rate'=>42,'form'=>['W','W','D','W','L'],'star_player'=>'Marcel Sabitzer','fifa_rank'=>27],
            'JOR' => ['code'=>'JOR','world_cups'=>1,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>0.8,'avg_goals_conceded'=>1.3,'win_rate'=>28,'form'=>['D','L','W','D','L'],'star_player'=>'Yazan Al-Naimat','fifa_rank'=>71],
            'COD' => ['code'=>'COD','world_cups'=>2,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>0.9,'avg_goals_conceded'=>1.1,'win_rate'=>30,'form'=>['W','L','D','L','W'],'star_player'=>'Yoane Wissa','fifa_rank'=>52],
            'UZB' => ['code'=>'UZB','world_cups'=>1,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>0.8,'avg_goals_conceded'=>1.2,'win_rate'=>28,'form'=>['D','W','L','D','L'],'star_player'=>'Eldor Shomurodov','fifa_rank'=>63],
            'GHA' => ['code'=>'GHA','world_cups'=>4,'best_result'=>'Cuartos','titles'=>0,'avg_goals_scored'=>1.1,'avg_goals_conceded'=>1.1,'win_rate'=>36,'form'=>['L','W','D','L','W'],'star_player'=>'Mohammed Kudus','fifa_rank'=>74],
            'PAN' => ['code'=>'PAN','world_cups'=>2,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>0.7,'avg_goals_conceded'=>1.3,'win_rate'=>25,'form'=>['L','D','L','W','D'],'star_player'=>'Rolando Blackburn','fifa_rank'=>49],
            'CPV' => ['code'=>'CPV','world_cups'=>1,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>0.8,'avg_goals_conceded'=>1.2,'win_rate'=>28,'form'=>['W','D','L','D','L'],'star_player'=>'Garry Rodrigues','fifa_rank'=>69],
            'KSA' => ['code'=>'KSA','world_cups'=>6,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>1.0,'avg_goals_conceded'=>1.2,'win_rate'=>32,'form'=>['L','W','D','L','W'],'star_player'=>'Salem Al-Dawsari','fifa_rank'=>56],
            'NZL' => ['code'=>'NZL','world_cups'=>2,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>0.7,'avg_goals_conceded'=>1.4,'win_rate'=>22,'form'=>['L','L','D','W','L'],'star_player'=>'Chris Wood','fifa_rank'=>85],
            'EGY' => ['code'=>'EGY','world_cups'=>3,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>1.0,'avg_goals_conceded'=>1.0,'win_rate'=>35,'form'=>['W','D','W','L','D'],'star_player'=>'Mohamed Salah','fifa_rank'=>34],
            'IRN' => ['code'=>'IRN','world_cups'=>6,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>1.0,'avg_goals_conceded'=>1.1,'win_rate'=>33,'form'=>['D','W','L','D','W'],'star_player'=>'Mehdi Taremi','fifa_rank'=>22],
            'TUN' => ['code'=>'TUN','world_cups'=>6,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>0.9,'avg_goals_conceded'=>1.0,'win_rate'=>30,'form'=>['D','L','W','D','L'],'star_player'=>'Wahbi Khazri','fifa_rank'=>30],
            'SWE' => ['code'=>'SWE','world_cups'=>12,'best_result'=>'3er lugar','titles'=>0,'avg_goals_scored'=>1.4,'avg_goals_conceded'=>0.9,'win_rate'=>48,'form'=>['W','W','D','W','D'],'star_player'=>'Viktor Gyökeres','fifa_rank'=>25],
            'BIH' => ['code'=>'BIH','world_cups'=>2,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>1.1,'avg_goals_conceded'=>1.2,'win_rate'=>35,'form'=>['W','D','L','W','D'],'star_player'=>'Edin Džeko','fifa_rank'=>65],
            'CUW' => ['code'=>'CUW','world_cups'=>1,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>0.6,'avg_goals_conceded'=>1.5,'win_rate'=>18,'form'=>['L','D','L','L','W'],'star_player'=>'Cuco Martina','fifa_rank'=>82],
            'CIV' => ['code'=>'CIV','world_cups'=>3,'best_result'=>'Grupos','titles'=>0,'avg_goals_scored'=>1.1,'avg_goals_conceded'=>1.1,'win_rate'=>36,'form'=>['W','D','L','W','D'],'star_player'=>'Sébastien Haller','fifa_rank'=>48],
        ];
    }
}
