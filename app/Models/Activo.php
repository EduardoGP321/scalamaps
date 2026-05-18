<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activo extends Model
{
    protected $fillable = [
        'tramo_id',
        'tipo_activo_id',
        'cadenamiento',
        'latitud',
        'longitud',
        'lado'
    ];

    public function obraMenor()
    {
        return $this->hasOne(ObraMenor::class);
    }
    public function conceptos()
    {
        return $this->hasMany(ActivoConcepto::class);
    }
    public function tipoActivo()
    {
        return $this->belongsTo(TipoActivo::class);
    }
    public function tramo()
    {
        return $this->belongsTo(Tramo::class);
    }

    protected static function booted()
    {
        static::saving(function ($activo) {
            if ($activo->tipo_activo_id == 1 && $activo->obraMenor) {
                $obra = $activo->obraMenor;

                if ($obra->latitud_inicio && $obra->latitud_fin) {
                    $activo->latitud = ($obra->latitud_inicio + $obra->latitud_fin) / 2;
                    $activo->longitud = ($obra->longitud_inicio + $obra->longitud_fin) / 2;
                }
            }
        });
    }
}
