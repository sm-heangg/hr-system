<?php

namespace App\Filament\Employee\Resources\LeaveRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name'),
                TextColumn::make('leaveType.name'),
                TextColumn::make('start_date')
                    ->badge()
                    ->color('success')
                    ->date(),
                TextColumn::make('end_date')
                    ->badge()
                    ->color('danger')
                    ->date(),
                TextColumn::make('days'),
                TextInputColumn::make('reason'),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'warning' => 'pending'
                    ]),
                TextColumn::make('approved_by'),
                TextColumn::make('approved_at'),
                TextColumn::make('rejection_reason'),

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
