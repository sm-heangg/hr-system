<?php

namespace App\Filament\Employee\Resources\Attendances\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Textarea;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Realtime clock display
                ViewField::make('clock')
                    ->label('Current Time')
                    ->view('filament.employee.attendance.live-clock'),

                ToggleButtons::make('status')
                    ->options([
                        'Present' => 'Present',
                        'Absent'  => 'Absent',
                        'Late'    => 'Late',
                        'Leave'   => 'Leave',
                    ])
                    ->grouped()
                    ->default('Present')
                    ->live()
                    ->required(),

                Textarea::make('notes')
                    ->placeholder('Your Late Reason...')
                    ->columnSpanFull()
                    ->visible(fn($get) => $get('status') === 'Late')
                    ->required(fn($get) => $get('status') === 'Late'),
            ]);
    }
}
