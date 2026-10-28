<?php

namespace App\Filament\Exports;

use App\Models\ObraMenor;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ObraMenorExporter extends Exporter
{
    protected static ?string $model = ObraMenor::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('activo_id'),
            ExportColumn::make('area_hidraulica'),
            ExportColumn::make('altura_terraplen_entrada'),
            ExportColumn::make('altura_terraplen_salida'),
            ExportColumn::make('longitud_estructura'),
            ExportColumn::make('esviaje'),
            ExportColumn::make('pendiente'),
            ExportColumn::make('subtipo'),
            ExportColumn::make('material'),
            ExportColumn::make('dimensiones'),
            ExportColumn::make('observaciones'),
            ExportColumn::make('latitud_inicio'),
            ExportColumn::make('longitud_inicio'),
            ExportColumn::make('latitud_fin'),
            ExportColumn::make('longitud_fin'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your obra menor export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
