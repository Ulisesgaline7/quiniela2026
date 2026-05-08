<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = ['name', 'short_name', 'flag', 'confederation', 'wc_group_id', 'fifa_ranking', 'api_code'];

    public function wcGroup(): BelongsTo
    {
        return $this->belongsTo(WcGroup::class, 'wc_group_id');
    }

    public function homeMatches(): HasMany
    {
        return $this->hasMany(WorldMatch::class, 'home_team_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(WorldMatch::class, 'away_team_id');
    }

    public function getDisplayAttribute(): string
    {
        return $this->flag . ' ' . $this->name;
    }
}
