<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuinielaGroupPick extends Model
{
    protected $table = 'quiniela_group_picks';
    protected $fillable = ['quiniela_id','wc_group_id','team_id','position','correct','points_earned'];

    public function quiniela(): BelongsTo { return $this->belongsTo(Quiniela::class); }
    public function group(): BelongsTo   { return $this->belongsTo(WcGroup::class, 'wc_group_id'); }
    public function team(): BelongsTo    { return $this->belongsTo(Team::class); }
}
