<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carretera extends Model
{
    protected $fillable = ['nombre'];
    public function tramos()
    {
        return $this->hasMany(Tramo::class);
    }
}
