<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'username', 'is_admin'];

    protected $hidden = ['remember_token'];

    protected function casts(): array
    {
        return [
            'is_admin' => 'boolean',
        ];
    }

    // No password needed — username-only login
    public function getAuthPassword() { return null; }

    public function quiniela(): HasOne
    {
        return $this->hasOne(Quiniela::class);
    }
}
