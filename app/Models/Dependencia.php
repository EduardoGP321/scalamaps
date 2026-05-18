<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    protected $fillable = [
        'nombre',
        'siglas',
        'logo',
        'tipo'
    ];
    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }
}
