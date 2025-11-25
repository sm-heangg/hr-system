<?php

namespace App\Filament\Admin\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee.user', 'name'),
                DatePicker::make('date')
                    ->required(),
                TimePicker::make('check_in'),
                TimePicker::make('check_out'),
                ToggleButtons::make('status')
                    ->options([
                        'Present' => 'Present',
                        'Absent'  => 'Absent',
                        'Late'    => 'Late',
                        'Leave'   => 'Leave'
                    ])
                    ->grouped()
                    ->live()
                    ->default('Present')
                    ->required(),
                Textarea::make('notes')
                    ->placeholder('Your Late Reason...')
                    ->visible(fn($get) => $get('status') === 'Late' || $get('status') === 'Leave')
                    ->required(fn($get) => $get('status') === 'Late' || $get('status') === 'Leave')
                    ->columnSpanFull()


            ]);
    }
}
