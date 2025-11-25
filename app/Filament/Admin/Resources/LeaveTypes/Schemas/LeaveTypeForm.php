<?php

namespace App\Filament\Admin\Resources\LeaveTypes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class LeaveTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('days_per_year')
                    ->label('Day/Year')
                    ->numeric(),
                ToggleButtons::make('is_paid')
                    ->boolean()
                    ->grouped()
                    ->default(1),
                Textarea::make('notes')
                    ->placeholder('Describe leave type...')

            ]);
    }
}
