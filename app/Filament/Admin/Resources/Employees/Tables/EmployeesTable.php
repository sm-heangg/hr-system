<?php

namespace App\Filament\Admin\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                    ->color('info'),
                TextColumn::make('salary')
                    ->sortable(),
                IconColumn::make('status')
                    ->boolean()
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
