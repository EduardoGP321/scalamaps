<?php

namespace App\Filament\Resources\Partidas;

use App\Filament\Resources\Partidas\Pages\CreatePartida;
use App\Filament\Resources\Partidas\Pages\EditPartida;
use App\Filament\Resources\Partidas\Pages\ListPartidas;
use App\Filament\Resources\Partidas\Pages\ViewPartida;
use App\Filament\Resources\Partidas\Schemas\PartidaForm;
use App\Filament\Resources\Partidas\Schemas\PartidaInfolist;
use App\Filament\Resources\Partidas\Tables\PartidasTable;
use App\Models\Partida;
use BackedEnum;
use Daljo25\FilamentTablerIcons\Enums\TablerIcon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PartidaResource extends Resource
{
    protected static ?string $model = Partida::class;

    protected static string|BackedEnum|null $navigationIcon = TablerIcon::Stack2Filled;

    protected static string|UnitEnum|null $navigationGroup = 'Catálogo F-11';


    public static function form(Schema $schema): Schema
    {
        return PartidaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PartidaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartidasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartidas::route('/'),
            'create' => CreatePartida::route('/create'),
            'view' => ViewPartida::route('/{record}'),
            'edit' => EditPartida::route('/{record}/edit'),
        ];
    }
}
