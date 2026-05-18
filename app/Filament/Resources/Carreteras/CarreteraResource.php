<?php

namespace App\Filament\Resources\Carreteras;

use App\Filament\Resources\Carreteras\Pages\CreateCarretera;
use App\Filament\Resources\Carreteras\Pages\EditCarretera;
use App\Filament\Resources\Carreteras\Pages\ListCarreteras;
use App\Filament\Resources\Carreteras\Pages\ViewCarretera;
use App\Filament\Resources\Carreteras\Schemas\CarreteraForm;
use App\Filament\Resources\Carreteras\Schemas\CarreteraInfolist;
use App\Filament\Resources\Carreteras\Tables\CarreterasTable;
use App\Models\Carretera;
use BackedEnum;
use Daljo25\FilamentTablerIcons\Enums\TablerIcon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CarreteraResource extends Resource
{
    protected static ?string $model = Carretera::class;

    protected static string|BackedEnum|null $navigationIcon = TablerIcon::MapPinFilled;

    protected static string|UnitEnum|null $navigationGroup = 'Estructura Vial';




    public static function form(Schema $schema): Schema
    {
        return CarreteraForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CarreteraInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarreterasTable::configure($table);
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
            'index' => ListCarreteras::route('/'),
            'create' => CreateCarretera::route('/create'),
            'view' => ViewCarretera::route('/{record}'),
            'edit' => EditCarretera::route('/{record}/edit'),
        ];
    }
}
