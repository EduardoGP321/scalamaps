<?php

namespace App\Filament\Resources\Carreteras\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CarreteraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
            ]);
    }
}
