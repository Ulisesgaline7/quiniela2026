<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorldMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'home_team_id', 'away_team_id', 'phase', 'group_name', 'matchday',
        'kickoff_at', 'closes_at', 'is_open', 'home_score', 'away_score',
        'had_extra_time', 'had_penalties', 'had_red_card',
        'had_penalty_in_game', 'had_stoppage_goal',
        'first_scorer_team_id', 'venue', 'city', 'status', 'api_match_id',
    ];

    protected $casts = [
        'kickoff_at'          => 'datetime',
        'closes_at'           => 'datetime',
        'is_open'             => 'boolean',
        'had_extra_time'      => 'boolean',
        'had_penalties'       => 'boolean',
        'had_red_card'        => 'boolean',
        'had_penalty_in_game' => 'boolean',
        'had_stoppage_goal'   => 'boolean',
    ];

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function firstScorerTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'first_scorer_team_id');
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(MatchPrediction::class, 'match_id');
    }

    public function isFinished(): bool
    {
        return $this->home_score !== null && $this->away_score !== null;
    }

    /**
     * Returns true if predictions are still open (now < closes_at).
     * Falls back to is_open flag if closes_at is not set.
     */
    public function isOpen(): bool
    {
        if ($this->closes_at) {
            return now()->lt($this->closes_at);
        }
        return (bool) $this->is_open;
    }

    public function getResultAttribute(): ?string
    {
        if (!$this->isFinished()) return null;
        if ($this->home_score > $this->away_score) return 'home';
        if ($this->home_score < $this->away_score) return 'away';
        return 'draw';
    }

    public function getPhaseLabel(): string
    {
        return self::phaseName($this->phase);
    }

    public static function phaseName(string $phase): string
    {
        return match($phase) {
            'groups'       => 'Fase de Grupos',
            'round_of_16'  => 'Octavos de Final',
            'quarters'     => 'Cuartos de Final',
            'semis'        => 'Semifinales',
            'third_place'  => 'Tercer Lugar',
            'final'        => 'Final',
            default        => ucfirst($phase),
        };
    }
}
