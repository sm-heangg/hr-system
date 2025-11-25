<?php

namespace App\Filament\Admin\Resources\Attendances\Tables;

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
                TextColumn::make('employee.employee_code')
                    ->label('Employee ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('employee.user.name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('shift')
                    ->label('Shift')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => ucfirst($state)),

                TextColumn::make('check_in')
                    ->label('Check in')
                    ->time('H:i:s'),

                TextColumn::make('check_out')
                    ->label('Check out')
                    ->time('H:i:s'),

                // Status with "Late (40m)" etc.
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'present',
                        'warning' => 'late',
                        'danger'  => 'absent',
                        'info'    => 'leave',
                    ])
                    ->formatStateUsing(function (string $state, \App\Models\Attendance $record) {
                        if ($record->late_by) {
                            return 'Late (' . $record->late_by . ')';
                        }

                        if ($record->early_by) {
                            return 'Early (' . $record->early_by . ')';
                        }

                        return ucfirst($state); // Present / Leave / Absent
                    }),

                // Optional separate "Late by" column
                // TextColumn::make('late_by')
                //     ->label('Late by')
                //     ->getStateUsing(fn(Attendance $record) => $record->late_by ?? '—'),

                // Leave type (only for leave days)
                TextColumn::make('leaveRequest.leaveType.name')
                    ->label('Leave type')
                    ->getStateUsing(
                        fn(Attendance $record) =>
                        $record->status === 'leave'
                            ? $record->leaveRequest?->leaveType?->name
                            : null
                    ),

                // Approved by (only for leave days)
                TextColumn::make('leaveRequest.approver.name')
                    ->label('Approved by')
                    ->getStateUsing(
                        fn(Attendance $record) =>
                        $record->status === 'leave'
                            ? $record->leaveRequest?->approver?->name
                            : null
                    ),
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
