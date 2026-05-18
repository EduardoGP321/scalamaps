<?php

namespace App\Filament\Resources\Dependencias;

use App\Filament\Resources\Dependencias\Pages\CreateDependencia;
use App\Filament\Resources\Dependencias\Pages\EditDependencia;
use App\Filament\Resources\Dependencias\Pages\ListDependencias;
use App\Filament\Resources\Dependencias\Pages\ViewDependencia;
use App\Filament\Resources\Dependencias\Schemas\DependenciaForm;
use App\Filament\Resources\Dependencias\Schemas\DependenciaInfolist;
use App\Filament\Resources\Dependencias\Tables\DependenciasTable;
use App\Models\Dependencia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DependenciaResource extends Resource
{
    protected static ?string $model = Dependencia::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    protected static string|UnitEnum|null $navigationGroup = 'Directorio';


    public static function form(Schema $schema): Schema
    {
        return DependenciaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DependenciaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DependenciasTable::configure($table);
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
            'index' => ListDependencias::route('/'),
            'create' => CreateDependencia::route('/create'),
            'view' => ViewDependencia::route('/{record}'),
            'edit' => EditDependencia::route('/{record}/edit'),
        ];
    }
}
