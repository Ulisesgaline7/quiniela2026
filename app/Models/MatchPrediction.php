<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchPrediction extends Model
{
    protected $fillable = [
        'user_id', 'match_id', 'home_score', 'away_score', 'result',
        'first_scorer_team_id', 'predict_red_card', 'predict_extra_time', 'predict_penalties',
        'pts_exact', 'pts_result', 'pts_diff', 'pts_first_scorer',
        'pts_red_card', 'pts_extra_time', 'pts_penalties', 'total_points', 'scored',
    ];

    protected $casts = [
        'predict_red_card'    => 'boolean',
        'predict_extra_time'  => 'boolean',
        'predict_penalties'   => 'boolean',
        'scored'              => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(WorldMatch::class, 'match_id');
    }

    public function firstScorerTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'first_scorer_team_id');
    }
}
