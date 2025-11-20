<?php

namespace App\Filament\Admin\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Username')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('employee_code')
                    ->required(),
                Select::make('department_id')
                    ->relationship('department', 'name'),
                Select::make('position_id')
                    ->relationship('position', 'name'),
                DatePicker::make('hire_date')
                    ->required(),
                TextInput::make('salary')
                    ->numeric()
                    ->default(null),
                ToggleButtons::make('status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Stop',
                    ])
                    ->grouped()
                    ->default('1')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }
}
