<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuinielaPhasePickModel extends Model
{
    protected $table = 'quiniela_phase_picks';

    protected $fillable = ['quiniela_id', 'phase', 'team_id', 'points_earned'];

    public function quiniela(): BelongsTo
    {
        return $this->belongsTo(Quiniela::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
