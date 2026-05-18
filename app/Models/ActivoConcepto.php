<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivoConcepto extends Model
{
    protected $table = 'activo_concepto';
    protected $fillable = [
        'activo_id',
        'catalogo_concepto_id',
        'cantidad',
        'importe'
    ];

    public function catalogoConcepto()
    {
        return $this->belongsTo(CatalogoConcepto::class);
    }
}
