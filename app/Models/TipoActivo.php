<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoActivo extends Model
{
    protected $table = 'tipos_activos';
    protected $fillable = ['nombre', 'slug', 'descripcion'];
}
