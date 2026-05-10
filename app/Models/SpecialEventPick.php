<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialEventPick extends Model
{
    protected $fillable = ['user_id','event_type','team_id','player_name','correct','points_earned'];
    protected $casts = ['correct' => 'boolean'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function team(): BelongsTo { return $this->belongsTo(Team::class); }
}
