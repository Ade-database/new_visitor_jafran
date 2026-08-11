<?php

namespace App\Filament\Resources\Visits\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VisitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('visitor_id')
                    ->relationship('visitor', 'name')
                    ->required(),
                Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->default(null),
                Select::make('admin_id')
                    ->relationship('admin', 'name')
                    ->default(null),
                TextInput::make('input_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('purpose')
                    ->default(null),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'checked_in' => 'Checked in',
            'checked_out' => 'Checked out',
        ])
                    ->default('pending')
                    ->required(),
                Textarea::make('rejected_reason')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('submitted_at'),
                DateTimePicker::make('approved_at'),
                DateTimePicker::make('checked_in_at'),
                DateTimePicker::make('checked_out_at'),
            ]);
    }
}
