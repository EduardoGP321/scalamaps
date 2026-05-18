<?php

namespace App\Filament\Resources\Activos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ActivoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tramo_id')
                    ->numeric(),
                TextEntry::make('tipo_activo_id')
                    ->numeric(),
                TextEntry::make('kilometraje'),
                TextEntry::make('latitud')
                    ->numeric(),
                TextEntry::make('longitud')
                    ->numeric(),
                TextEntry::make('lado')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
