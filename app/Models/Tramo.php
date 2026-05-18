<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Tramo extends Model
{
    protected $fillable = [
        'contrato_id',
        'carretera_id',
        'nombre_tramo',
        'latitud_inicio',
        'longitud_inicio',
        'latitud_fin',
        'longitud_fin',
        'polyline_google'
    ];

    // RELACIONES PARA FILAMENT:
    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    public function carretera()
    {
        return $this->belongsTo(Carretera::class);
    }

    public function activos()
    {
        return $this->hasMany(Activo::class);
    }

    protected static function booted()
    {
        static::saving(function ($tramo) {
            if ($tramo->latitud_inicio && $tramo->latitud_fin) {

                $response = Http::withHeaders([
                    'X-Goog-Api-Key' => config('services.google_maps.key'),
                    'X-Goog-FieldMask' => 'routes.polyline.encodedPolyline',
                ])->post('https://routes.googleapis.com/directions/v2:computeRoutes', [
                    'origin' => [
                        'location' => [
                            'latLng' => [
                                'latitude' => (float) $tramo->latitud_inicio,
                                'longitude' => (float) $tramo->longitud_inicio
                            ]
                        ]
                    ],
                    'destination' => [
                        'location' => [
                            'latLng' => [
                                'latitude' => (float) $tramo->latitud_fin,
                                'longitude' => (float) $tramo->longitud_fin
                            ]
                        ]
                    ],
                    'travelMode' => 'DRIVE',
                    'polylineEncoding' => 'ENCODED_POLYLINE',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $tramo->polyline_google = $data['routes'][0]['polyline']['encodedPolyline'] ?? null;
                }
            }
        });
    }
}
