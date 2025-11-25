<?php

namespace App\Filament\Admin\Resources\Departments;

use App\Filament\Admin\Resources\Departments\Pages\CreateDepartment;
use App\Filament\Admin\Resources\Departments\Pages\EditDepartment;
use App\Filament\Admin\Resources\Departments\Pages\ListDepartments;
use App\Filament\Admin\Resources\Departments\Schemas\DepartmentForm;
use App\Filament\Admin\Resources\Departments\Tables\DepartmentsTable;
use App\Models\Department;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    public static function getNavigationBadge(): ?string
    {
        $count = Department::query()->count();

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
        return DepartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepartmentsTable::configure($table);
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
            'index' => ListDepartments::route('/'),
            'create' => CreateDepartment::route('/create'),
            'edit' => EditDepartment::route('/{record}/edit'),
        ];
    }
}
