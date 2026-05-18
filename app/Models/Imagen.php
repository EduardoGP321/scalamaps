<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Imagen extends Model
{
    protected $table = 'imagenes';

    protected $fillable = [
        'ruta',
        'titulo',
        'imageable_id',
        'imageable_type',
    ];

    /**
     * Relación Polimórfica Inversa.
     * Esto permite que este modelo se conecte automáticamente con ObraMenor,
     * Estructura (Puentes), Señalamientos o cualquier otro modelo futuro.
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Atributo Accesador (Accesor) para obtener la URL pública de la imagen de forma limpia.
     * En tu Blade o API solo llamarás: $imagen->url
     */
    public function getUrlAttribute(): ?string
    {
        if (!$this->ruta) {
            return null;
        }

        return Storage::disk('public')->url($this->ruta);
    }
}
