<?php

namespace App\Filament\Resources\ParteObras;

use App\Filament\Resources\ParteObras\Pages\CreateParteObra;
use App\Filament\Resources\ParteObras\Pages\EditParteObra;
use App\Filament\Resources\ParteObras\Pages\ListParteObras;
use App\Filament\Resources\ParteObras\Pages\ViewParteObra;
use App\Filament\Resources\ParteObras\Schemas\ParteObraForm;
use App\Filament\Resources\ParteObras\Schemas\ParteObraInfolist;
use App\Filament\Resources\ParteObras\Tables\ParteObrasTable;
use App\Models\ParteObra;
use BackedEnum;
use Daljo25\FilamentTablerIcons\Enums\TablerIcon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ParteObraResource extends Resource
{
    protected static ?string $model = ParteObra::class;

    protected static string|BackedEnum|null $navigationIcon = TablerIcon::AutomaticGearboxFilled;

    protected static string|UnitEnum|null $navigationGroup = 'Catálogo F-11';


    public static function form(Schema $schema): Schema
    {
        return ParteObraForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ParteObraInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ParteObrasTable::configure($table);
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
            'index' => ListParteObras::route('/'),
            'create' => CreateParteObra::route('/create'),
            'view' => ViewParteObra::route('/{record}'),
            'edit' => EditParteObra::route('/{record}/edit'),
        ];
    }
}




