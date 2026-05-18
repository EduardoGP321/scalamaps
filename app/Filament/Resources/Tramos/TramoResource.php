<?php

namespace App\Filament\Resources\Tramos;

use App\Filament\Resources\Tramos\Pages\CreateTramo;
use App\Filament\Resources\Tramos\Pages\EditTramo;
use App\Filament\Resources\Tramos\Pages\ListTramos;
use App\Filament\Resources\Tramos\Pages\ViewTramo;
use App\Filament\Resources\Tramos\Schemas\TramoForm;
use App\Filament\Resources\Tramos\Schemas\TramoInfolist;
use App\Filament\Resources\Tramos\Tables\TramosTable;
use App\Models\Tramo;
use BackedEnum;
use Daljo25\FilamentTablerIcons\Enums\TablerIcon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TramoResource extends Resource
{
    protected static ?string $model = Tramo::class;

    protected static string|BackedEnum|null $navigationIcon = TablerIcon::Road;

    protected static string|UnitEnum|null $navigationGroup = 'Operación';


    public static function form(Schema $schema): Schema
    {
        return TramoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TramoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TramosTable::configure($table);
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
            'index' => ListTramos::route('/'),
            'create' => CreateTramo::route('/create'),
            'view' => ViewTramo::route('/{record}'),
            'edit' => EditTramo::route('/{record}/edit'),
        ];
    }
}
