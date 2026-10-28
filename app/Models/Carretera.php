<?php

namespace App\Models;

use App\Traits\HasUserOwnership;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;

class Carretera extends Model
{
    use HasUserOwnership;

    protected $fillable = [
        'nombre',
        'latitud_inicio',
        'longitud_inicio',
        'latitud_fin',
        'longitud_fin',
        'polyline_google',
    ];

    protected static function applyUserOwnershipFilter(Builder $builder, $user): void
    {
        $builder->whereHas('tramos.contrato.users', function (Builder $query) use ($user) {
            $query->where('users.id', $user->id);
        });
    }
    public function contratos(): BelongsToMany
    {
        return $this->belongsToMany(Contrato::class, 'contrato_carretera');
    }


    public function tramos(): HasMany
    {
        return $this->hasMany(Tramo::class);
    }


    protected static function booted()
    {
        static::saving(function ($carretera) {
            if ($carretera->latitud_inicio && $carretera->latitud_fin) {

                $response = Http::withHeaders([
                    'X-Goog-Api-Key' => config('services.google_maps.key'),
                    'X-Goog-FieldMask' => 'routes.polyline.encodedPolyline',
                ])->post('https://routes.googleapis.com/directions/v2:computeRoutes', [
                    'origin' => [
                        'location' => [
                            'latLng' => [
                                'latitude' => (float) $carretera->latitud_inicio,
                                'longitude' => (float) $carretera->longitud_inicio
                            ]
                        ]
                    ],
                    'destination' => [
                        'location' => [
                            'latLng' => [
                                'latitude' => (float) $carretera->latitud_fin,
                                'longitude' => (float) $carretera->longitud_fin
                            ]
                        ]
                    ],
                    'travelMode' => 'DRIVE',
                    'polylineEncoding' => 'ENCODED_POLYLINE',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $carretera->polyline_google = $data['routes'][0]['polyline']['encodedPolyline'] ?? null;
                }
            }
        });
    }
}
