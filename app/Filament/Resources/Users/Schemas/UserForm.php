<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Contrato;
use Daljo25\FilamentTablerIcons\Enums\TablerIcon;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
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

                        FileUpload::make('avatar_url')
                            ->label('Foto de Perfil')
                            ->avatar()
                            ->disk('public')
                            ->directory('users/avatars')
                            ->visibility('public')
                            ->optimize('webp', 85)
                            ->maxImageWidth('1200')
                            ->maxImageHeight('1200')
                            ->image()
                    ]),

                Section::make('Asignación y Permisos')
                    ->columns(2)
                    ->schema([
                        Select::make('empresas')
                            ->label('Empresas Asignadas')
                            ->relationship('empresas', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),

                        Select::make('roles')
                            ->label('Roles / Permisos')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),

                        Select::make('dependency_id')
                            ->relationship('dependencia', 'siglas')
                            ->label('Dependencia / Institución')
                            ->prefixIcon(TablerIcon::Building)
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                // Limpiar contratos al cambiar dependencia
                                $set('contratos', []);
                            }),

                        // CORRECCIÓN PRINCIPAL:
                        // Se reemplaza ->relationship() con ->options() + ->saveRelationshipsUsing()
                        // porque el tercer argumento closure de ->relationship() en Filament v4
                        // NO inyecta Get $get por tipo-hint — solo lo hace ->options().
                        Select::make('contratos')
                            ->label('Contratos Asignados para Supervisión')
                            ->prefixIcon(TablerIcon::FileText)
                            ->placeholder('Seleccione los contratos autorizados...')
                            ->multiple()
                            ->searchable()
                            ->live()
                            ->options(function (Get $get) {
                                $dependenciaId = $get('dependency_id');

                                if (! $dependenciaId) {
                                    return [];
                                }

                                return Contrato::withoutGlobalScope('user_ownership')
                                    ->where('dependencia_id', $dependenciaId)
                                    ->pluck('numero_contrato', 'id');
                            })
                            // 1. CARGA LOS DATOS AL EDITAR: formatStateUsing se ejecuta siempre al cargar el registro existente
                            ->formatStateUsing(function ($record) {
                                return $record ? $record->contratos()->pluck('contratos.id')->toArray() : [];
                            })
                            // 2. EVITA SOBREESCRITURA: Le dice a Filament que no intente guardar este campo directo en la tabla 'users'
                            ->dehydrated(false)
                            // 3. PROCESA LA RELACIÓN: Sincroniza correctamente en la tabla pivote de muchos a muchos
                            ->saveRelationshipsUsing(function ($component, $record) {
                                $record->contratos()->sync($component->getState() ?? []);
                            })
                            ->disabled(fn(Get $get) => ! $get('dependency_id')),

                        Toggle::make('is_active')
                            ->label('Usuario Activo')
                            ->default(true)
                            ->inline(false),
                    ]),
            ])
            ->columns(1);
    }
}
