<?php

namespace App\Filament\Admin\Resources\Positions;

use App\Filament\Admin\Resources\Positions\Pages\CreatePosition;
use App\Filament\Admin\Resources\Positions\Pages\EditPosition;
use App\Filament\Admin\Resources\Positions\Pages\ListPositions;
use App\Filament\Admin\Resources\Positions\Schemas\PositionForm;
use App\Filament\Admin\Resources\Positions\Tables\PositionsTable;
use App\Models\Position;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
    public static function getNavigationBadge(): ?string
    {
        $count = Position::query()->count();

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
        return PositionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PositionsTable::configure($table);
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
            'index' => ListPositions::route('/'),
            'create' => CreatePosition::route('/create'),
            'edit' => EditPosition::route('/{record}/edit'),
        ];
    }
}
