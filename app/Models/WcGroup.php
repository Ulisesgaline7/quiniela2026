<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WcGroup extends Model
{
    protected $table = 'wc_groups';
    protected $fillable = ['name', 'label'];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class, 'wc_group_id')->orderBy('fifa_ranking');
    }
}
