<?php

namespace App\Filament\Admin\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Carbon\Carbon;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('employee_code')
                    ->label('Employee ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Name'),
                TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position.name')
                    ->badge()
                    ->label('Position')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('hire_date')
                    ->badge()
                    ->color('info')
                    ->date(),
                IconColumn::make('status')
                    ->boolean(),


                TextColumn::make('shift_morning_start')
                    ->label('Morning Shift')
                    ->formatStateUsing(function ($state, $record) {
                        if (! $record->shift_morning_start || ! $record->shift_morning_end) {
                            return '-';
                        }

                        // hour only: 'H'  (07 - 12)
                        $start = Carbon::parse($record->shift_morning_start)->format('H');
                        $end   = Carbon::parse($record->shift_morning_end)->format('H');

                        return "{$start} - {$end}";
                    }),

                TextColumn::make('shift_afternoon_start')
                    ->label('Afternoon Shift')
                    ->formatStateUsing(function ($state, $record) {
                        if (! $record->shift_afternoon_start || ! $record->shift_afternoon_end) {
                            return '-';
                        }

                        $start = Carbon::parse($record->shift_afternoon_start)->format('H');
                        $end   = Carbon::parse($record->shift_afternoon_end)->format('H');

                        return "{$start} - {$end}";
                    }),



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
