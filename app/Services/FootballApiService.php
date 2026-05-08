<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FootballApiService
{
    private string $baseUrl = 'https://api.wc2026api.com/v1';
    private ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.wc2026.key');
    }

    private function headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept'        => 'application/json',
        ];
    }

    /**
     * Get all matches with live scores
     */
    public function getMatches(string $round = null): array
    {
        $cacheKey = 'wc2026_matches_' . ($round ?? 'all');
        $ttl = 60; // 1 minute cache for live data

        return Cache::remember($cacheKey, $ttl, function () use ($round) {
            try {
                $params = $round ? ['round' => $round] : [];
                $response = Http::timeout(10)
                    ->withHeaders($this->headers())
                    ->get("{$this->baseUrl}/matches", $params);

                if ($response->successful()) {
                    return $response->json('data', []);
                }
            } catch (\Exception $e) {
                Log::warning('WC2026 API error: ' . $e->getMessage());
            }
            return [];
        });
    }

    /**
     * Get group standings
     */
    public function getStandings(): array
    {
        return Cache::remember('wc2026_standings', 120, function () {
            try {
                $response = Http::timeout(10)
                    ->withHeaders($this->headers())
                    ->get("{$this->baseUrl}/standings");

                if ($response->successful()) {
                    return $response->json('data', []);
                }
            } catch (\Exception $e) {
                Log::warning('WC2026 API standings error: ' . $e->getMessage());
            }
            return [];
        });
    }

    /**
     * Get single match details with stats
     */
    public function getMatch(string $matchId): ?array
    {
        return Cache::remember("wc2026_match_{$matchId}", 60, function () use ($matchId) {
            try {
                $response = Http::timeout(10)
                    ->withHeaders($this->headers())
                    ->get("{$this->baseUrl}/matches/{$matchId}");

                if ($response->successful()) {
                    return $response->json('data');
                }
            } catch (\Exception $e) {
                Log::warning("WC2026 API match {$matchId} error: " . $e->getMessage());
            }
            return null;
        });
    }

    /**
     * Get team stats/history for head-to-head comparison
     * Uses cached static data since API may not have historical H2H
     */
    public function getTeamComparison(string $team1Code, string $team2Code): array
    {
        $cacheKey = "wc2026_h2h_{$team1Code}_{$team2Code}";

        return Cache::remember($cacheKey, 3600, function () use ($team1Code, $team2Code) {
            try {
                $response = Http::timeout(10)
                    ->withHeaders($this->headers())
                    ->get("{$this->baseUrl}/teams/{$team1Code}/vs/{$team2Code}");

                if ($response->successful()) {
                    return $response->json('data', []);
                }
            } catch (\Exception $e) {
                Log::warning("WC2026 H2H error: " . $e->getMessage());
            }

            // Fallback: return static comparison data
            return $this->staticTeamData($team1Code, $team2Code);
        });
    }

    /**
     * Static fallback team data for comparison when API unavailable
     */
    private function staticTeamData(string $t1, string $t2): array
    {
        $stats = $this->teamStats();
        return [
            'team1' => $stats[$t1] ?? $this->defaultStats($t1),
            'team2' => $stats[$t2] ?? $this->defaultStats($t2),
            'source' => 'static',
        ];
    }

    private function defaultStats(string $code): array
    {
        return [
            'code' => $code, 'world_cups' => 0, 'best_result' => 'Grupos',
            'titles' => 0, 'avg_goals_scored' => 1.2, 'avg_goals_conceded' => 1.1,
            'win_rate' => 33, 'form' => ['W','D','L','D','L'],
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
        ];
    }
}
