<?php

namespace App\Models;

use App\Traits\HasUserOwnership;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Activo extends Model
{
    use HasUserOwnership;

    protected $fillable = [
        'tramo_id',
        'tipo_activo_id',
        'cadenamiento',
        'condicion',
        'latitud',
        'longitud',
        'lado',
    ];

    protected static function applyUserOwnershipFilter(Builder $builder, $user): void
    {
        $builder->whereHas('tramo.contrato.users', function (Builder $query) use ($user) {
            $query->where('users.id', $user->id);
        });
    }


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
    public function imagenes()
    {
        return $this->morphMany(Imagen::class, 'imageable');
    }
}
