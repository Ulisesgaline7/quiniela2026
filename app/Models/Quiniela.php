<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiniela extends Model
{
    protected $fillable = [
        'user_id', 'submitted', 'submitted_at',
        'champion_id', 'runner_up_id', 'third_place_id',
        'golden_ball', 'golden_boot', 'golden_glove', 'best_young',
        'surprise_team_id', 'top_scorer_team_id', 'best_defense_id',
        'total_goals_guess',
        'points_podio', 'points_awards', 'points_stats', 'points_phases', 'total_points',
    ];

    protected $casts = [
        'submitted'    => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function champion(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'champion_id');
    }

    public function runnerUp(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'runner_up_id');
    }

    public function thirdPlace(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'third_place_id');
    }

    public function surpriseTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'surprise_team_id');
    }

    public function topScorerTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'top_scorer_team_id');
    }

    public function bestDefense(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'best_defense_id');
    }

    public function phasePicks(): HasMany
    {
        return $this->hasMany(QuinielaPhasePickModel::class);
    }

    public function groupPicks(): HasMany
    {
        return $this->hasMany(\App\Models\QuinielaGroupPick::class);
    }

    public function picksByPhase(string $phase): \Illuminate\Database\Eloquent\Collection
    {
        return $this->phasePicks()->where('phase', $phase)->with('team')->get();
    }
}
