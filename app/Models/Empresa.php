<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = [
        'nombre',
        'logo',
        'es_externa'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
