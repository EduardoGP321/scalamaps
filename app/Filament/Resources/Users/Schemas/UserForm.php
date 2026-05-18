<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información Personal')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre Completo')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(255),

                        TextInput::make('telefono')
                            ->label('Teléfono')
                            ->tel(),
                    ]),

                Section::make('Asignación y Permisos')
                    ->columns(2)
                    ->schema([
                        Select::make('empresas')
                            ->label('Empresas Asignadas')
                            ->relationship('empresas', 'nombre')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required(),

                        Select::make('roles')
                            ->label('Roles / Permisos')
                            ->options(Role::all()->pluck('name', 'id'))->multiple()
                            ->preload()
                            ->searchable(),

                        Select::make('dependency_id')
                            ->label('Dependencia')
                            ->relationship('dependencia', 'siglas')
                            ->searchable()
                            ->preload(),

                        Toggle::make('is_active')
                            ->label('Usuario Activo')
                            ->default(true)
                            ->inline(false),
                    ]),
            ])
            ->columns(1);
    }
}
