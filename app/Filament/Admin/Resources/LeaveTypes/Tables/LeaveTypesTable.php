<?php

namespace App\Filament\Admin\Resources\LeaveTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class LeaveTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('days_per_year')
                    ->label('Day/Year')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('is_paid')
                    ->label('Paid')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state ? 'Paid' : 'Unpaid')
                    ->colors([
                        'success' => fn($state) => $state == 1,   // green when paid
                        'danger'  => fn($state) => $state == 0,   // red when unpaid
                    ])
                    ->sortable()
                    ->searchable(),
                TextInputColumn::make('notes')
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
