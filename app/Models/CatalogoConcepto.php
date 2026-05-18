<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogoConcepto extends Model
{
    protected $table = 'catalogo_conceptos';

    protected $fillable = [
        'parte_obra_id',
        'partida_id',
        'clave',
        'concepto',
        'unidad',
        'precio_unitario'
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
    ];

    /**
     * Relación con la Parte de la Obra
     */
    public function parteObra(): BelongsTo
    {
        return $this->belongsTo(ParteObra::class, 'parte_obra_id');
    }

    /**
     * Relación con la Partida
     */
    public function partida(): BelongsTo
    {
        return $this->belongsTo(Partida::class, 'partida_id');
    }
}
