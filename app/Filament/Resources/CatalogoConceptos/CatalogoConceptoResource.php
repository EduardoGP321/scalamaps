<?php

namespace App\Filament\Resources\CatalogoConceptos;

use App\Filament\Resources\CatalogoConceptos\Pages\CreateCatalogoConcepto;
use App\Filament\Resources\CatalogoConceptos\Pages\EditCatalogoConcepto;
use App\Filament\Resources\CatalogoConceptos\Pages\ListCatalogoConceptos;
use App\Filament\Resources\CatalogoConceptos\Pages\ViewCatalogoConcepto;
use App\Filament\Resources\CatalogoConceptos\Schemas\CatalogoConceptoForm;
use App\Filament\Resources\CatalogoConceptos\Schemas\CatalogoConceptoInfolist;
use App\Filament\Resources\CatalogoConceptos\Tables\CatalogoConceptosTable;
use App\Models\CatalogoConcepto;
use BackedEnum;
use Daljo25\FilamentTablerIcons\Enums\TablerIcon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CatalogoConceptoResource extends Resource
{
    protected static ?string $model = CatalogoConcepto::class;

    protected static string|BackedEnum|null $navigationIcon = TablerIcon::ReceiptFilled;

    protected static string|UnitEnum|null $navigationGroup = 'Catálogo F-11';


    public static function form(Schema $schema): Schema
    {
        return CatalogoConceptoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CatalogoConceptoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CatalogoConceptosTable::configure($table);
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
            'index' => ListCatalogoConceptos::route('/'),
            'create' => CreateCatalogoConcepto::route('/create'),
            'view' => ViewCatalogoConcepto::route('/{record}'),
            'edit' => EditCatalogoConcepto::route('/{record}/edit'),
        ];
    }
}
