<?php

namespace App\Filament\Admin\Resources\LeaveRequests;

use App\Filament\Admin\Resources\LeaveRequests\Pages\CreateLeaveRequest;
use App\Filament\Admin\Resources\LeaveRequests\Pages\EditLeaveRequest;
use App\Filament\Admin\Resources\LeaveRequests\Pages\ListLeaveRequests;
use App\Filament\Admin\Resources\LeaveRequests\Schemas\LeaveRequestForm;
use App\Filament\Admin\Resources\LeaveRequests\Tables\LeaveRequestsTable;
use App\Models\LeaveRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-top-right-on-square';
    protected static string|UnitEnum|null $navigationGroup = 'Leaves';

    /**
     * Show a badge with the number of pending requests.
     * Returns null when there are none (badge hidden).
     */
    public static function getNavigationBadge(): ?string
    {
        $count = LeaveRequest::where('status', 'pending')->count();

        return $count > 0 ? (string) $count : null;
    }

    /**
     * Color of the navigation badge.
     */
    public static function getNavigationBadgeColor(): ?string
    {
        // 'danger' = red pill
        return 'danger';
    }

    public static function form(Schema $schema): Schema
    {
        return LeaveRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // no relations yet
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLeaveRequests::route('/'),
            'create' => CreateLeaveRequest::route('/create'),
            'edit'   => EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
