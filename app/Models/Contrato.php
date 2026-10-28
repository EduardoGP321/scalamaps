<?php

namespace App\Models;

use App\Traits\HasUserOwnership;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Contrato extends Model
{

    use HasUserOwnership;

    protected $fillable = [
        'dependencia_id',
        'empresa_id',
        'numero_contrato',
        'nombre_completo',
        'nombre_corto',
        'fecha_inicio',
        'fecha_fin',
        'dias_contrato',
    ];


    public function carreteras(): BelongsToMany
    {
        return $this->belongsToMany(Carretera::class, 'contrato_carretera');
    }
    public function tramos(): HasMany
    {
        return $this->hasMany(Tramo::class);
    }
    public function activos(): HasManyThrough
    {
        return $this->hasManyThrough(Activo::class, Tramo::class);
    }

    public function partesObra(): HasMany
    {
        return $this->hasMany(ParteObra::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function dependencia(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
