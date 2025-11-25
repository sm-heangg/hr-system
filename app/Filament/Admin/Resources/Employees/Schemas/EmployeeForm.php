<?php

namespace App\Filament\Admin\Resources\Employees\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
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
                    ->label('Employee Code')
                    ->default(function ($record) {
                        // Edit → keep existing
                        if ($record && $record->exists) {
                            return $record->employee_code;
                        }

                        // Create → generate next EMPxxx
                        return Employee::nextCode();
                    })
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->hint('Automatically generated'),

                Select::make('department_id')
                    ->relationship('department', 'name'),

                Select::make('position_id')
                    ->relationship('position', 'name'),

                DatePicker::make('hire_date')
                    ->label('Hire date')
                    ->required(),

                TextInput::make('salary')
                    ->numeric()
                    ->default(null),

                // Morning shift start / end
                TimePicker::make('shift_morning_start')
                    ->label('Morning start')
                    ->seconds(false)
                    ->nullable(),

                TimePicker::make('shift_morning_end')
                    ->label('Morning end')
                    ->seconds(false)
                    ->nullable(),

                // Afternoon shift start / end
                TimePicker::make('shift_afternoon_start')
                    ->label('Afternoon start')
                    ->seconds(false)
                    ->nullable(),

                TimePicker::make('shift_afternoon_end')
                    ->label('Afternoon end')
                    ->seconds(false)
                    ->nullable(),

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
