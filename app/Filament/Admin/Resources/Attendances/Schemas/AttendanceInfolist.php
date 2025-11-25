<?php

namespace App\Filament\Admin\Resources\Attendances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AttendanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('employee.user.name')
                    ->label('Employee'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('check_in')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('check_out')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge()
                    ->color('success'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
            ]);
    }
}
