<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'es_externa'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class, 'team_id');
    }
}
