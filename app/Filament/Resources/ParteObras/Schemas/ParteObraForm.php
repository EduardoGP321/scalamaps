<?php

namespace App\Filament\Resources\ParteObras\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ParteObraForm
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
