<?php

namespace App\Filament\Resources\TipoActivos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;

class TipoActivoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Definición del Tipo de Activo')
                    ->description('Categoriza los elementos que se encontrarán en la carretera.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nombre')
                            ->label('Nombre del Tipo')
                            ->placeholder('Ej. Alcantarilla, Señalización, Puente')
                            ->required()
                            ->live(onBlur: true) // Detecta cuando dejas de escribir
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->label('Identificador (Slug)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->readonly() // Evita cambios accidentales que rompan la lógica
                            ->helperText('Se genera automáticamente basado en el nombre.'),

                        Textarea::make('descripcion')
                            ->label('Descripción técnica')
                            ->placeholder('Opcional: Describe las características generales de este tipo de activo.')
                            ->columnSpanFull()
                            ->rows(3),
                    ]),
            ])
            ->columns(1);
    }
}
