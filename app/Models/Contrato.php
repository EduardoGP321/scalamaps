<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $fillable = [
        'dependencia_id',
        'carretera_id',
        'empresa_id',
        'numero_contrato',
        'nombre_completo',
        'nombre_corto',
        'fecha_inicio',
        'fecha_fin',
        'dias_contrato'
    ];
    public function tramos()
    {
        return $this->hasMany(Tramo::class);
    }
    public function partesObra()
    {
        return $this->hasMany(ParteObra::class);
    }
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }
    public function carretera()
    {
        return $this->belongsTo(Carretera::class);
    }
}
