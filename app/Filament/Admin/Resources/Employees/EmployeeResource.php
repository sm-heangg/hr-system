<?php

namespace App\Filament\Admin\Resources\Employees;

use App\Filament\Admin\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Admin\Resources\Employees\Pages\EditEmployee;
use App\Filament\Admin\Resources\Employees\Pages\ListEmployees;
use App\Filament\Admin\Resources\Employees\Schemas\EmployeeForm;
use App\Filament\Admin\Resources\Employees\Tables\EmployeesTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    /**
     * Show total number of employees as a badge.
     */
    public static function getNavigationBadge(): ?string
    {
        $count = Employee::query()->count();

        // If you *always* want to show it, even 0, just:
        return (string) $count;

        // If you want to hide badge when 0, use:
        // return $count > 0 ? (string) $count : null;
    }

    /**
     * Optional: color of the badge.
     */
    public static function getNavigationBadgeColor(): ?string
    {
        return 'info'; // blue badge (you can use 'primary', 'success', etc.)
    }

    public static function form(Schema $schema): Schema
    {
        return EmployeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'edit'   => EditEmployee::route('/{record}/edit'),
        ];
    }
}
