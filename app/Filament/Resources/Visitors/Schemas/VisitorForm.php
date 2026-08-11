<?php

namespace App\Filament\Resources\Visitors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VisitorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone_number')
                    ->tel()
                    ->default(null),
                TextInput::make('company')
                    ->default(null),
                Select::make('category')
                    ->options(['reguler' => 'Reguler', 'prioritas' => 'Prioritas'])
                    ->default('reguler')
                    ->required(),
                TextInput::make('photo_path')
                    ->required(),
            ]);
    }
}
