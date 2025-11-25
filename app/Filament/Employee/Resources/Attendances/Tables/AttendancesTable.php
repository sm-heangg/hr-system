<?php

namespace App\Filament\Employee\Resources\Attendances\Tables;

use App\Models\Attendance;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.user.name')
                    ->label("Name"),
                TextColumn::make('date')
                    ->date(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'present',
                        'warning' => 'late',
                        'danger'  => 'absent',
                        'info'    => 'leave',
                    ])
                    ->formatStateUsing(function (string $state, Attendance $record) {
                        if ($state === 'late' && $record->late_by) {
                            return "Late ({$record->late_by})";
                        }

                        return ucfirst($state);
                    })
                    ->label('Status'),
                TextColumn::make('late_by')
                    ->label('Late by')
                    ->sortable(false)
                    ->getStateUsing(fn(Attendance $record) => $record->late_by ?? '—'),
                TextColumn::make('check_in'),
                TextColumn::make('check_out'),


            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
