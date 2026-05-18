<?php

namespace App\Filament\Resources\TipoActivos;

use App\Filament\Resources\TipoActivos\Pages\CreateTipoActivo;
use App\Filament\Resources\TipoActivos\Pages\EditTipoActivo;
use App\Filament\Resources\TipoActivos\Pages\ListTipoActivos;
use App\Filament\Resources\TipoActivos\Pages\ViewTipoActivo;
use App\Filament\Resources\TipoActivos\Schemas\TipoActivoForm;
use App\Filament\Resources\TipoActivos\Schemas\TipoActivoInfolist;
use App\Filament\Resources\TipoActivos\Tables\TipoActivosTable;
use App\Models\TipoActivo;
use BackedEnum;
use Daljo25\FilamentTablerIcons\Enums\TablerIcon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TipoActivoResource extends Resource
{
    protected static ?string $model = TipoActivo::class;

    protected static string|BackedEnum|null $navigationIcon = TablerIcon::Category2;

        protected static string|UnitEnum|null $navigationGroup = 'Estructura Vial';


    public static function form(Schema $schema): Schema
    {
        return TipoActivoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TipoActivoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipoActivosTable::configure($table);
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
            'index' => ListTipoActivos::route('/'),
            'create' => CreateTipoActivo::route('/create'),
            'view' => ViewTipoActivo::route('/{record}'),
            'edit' => EditTipoActivo::route('/{record}/edit'),
        ];
    }
}
