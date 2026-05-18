<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObraMenor extends Model
{
    protected $table = 'obras_menores';
    protected $fillable = [
        'activo_id',
        'cuerpo',
        'area_hidraulica',
        'altura_terraplen_entrada',
        'altura_terraplen_salida',
        'longitud_estructura',
        'esviaje',
        'pendiente',
        'subtipo',
        'material',
        'dimensiones',
        'observaciones',
        'condicion',
        'latitud_inicio',
        'longitud_inicio',
        'latitud_fin',
        'longitud_fin',
    ];

    protected $casts = [
        'latitud_inicio' => 'float',
        'longitud_inicio' => 'float',
        'latitud_fin' => 'float',
        'longitud_fin' => 'float',
    ];

    public function activo()
    {
        return $this->belongsTo(Activo::class);
    }

    public function imagenes()
    {
        return $this->morphMany(Imagen::class, 'imageable');
    }
}
