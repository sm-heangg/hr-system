<?php

namespace App\Filament\Employee\Resources\LeaveRequests\Schemas;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(Auth::user()->id)
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                Select::make('leave_type_id')
                    ->relationship('leaveType', 'name')
                    ->searchable()
                    ->preload()
                    ->live()  // 👈 so afterStateUpdated fires
                    ->afterStateUpdated(
                        fn($state, Get $get, Set $set) =>
                        self::updateRemainingDays($get, $set)
                    )
                    ->required(),
                DatePicker::make('start_date')
                    ->minDate(now()->subDay())
                    ->live()
                    ->required()
                    ->afterStateUpdated(
                        fn($state, Get $get, Set $set) =>
                        self::calculateDays($get, $set)
                    ),
                DatePicker::make('end_date')
                    ->minDate(now())
                    ->live()
                    ->required()
                    ->afterStateUpdated(
                        fn($state, Get $get, Set $set) =>
                        self::calculateDays($get, $set)
                    ),
                TextInput::make('days')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->numeric(),
                TextInput::make('remaining_days')
                    ->label('Days Left This Year')
                    ->disabled()
                    ->dehydrated(false),
                Textarea::make('reason')
                    ->required()
                    ->columnSpanFull(),
                // Select::make('status')
                //     ->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'])
                //     ->default('pending')
                //     ->required(),
                Hidden::make('status')
                    ->default('pending')
            ]);
    }
    protected static function calculateDays(Get $get, Set $set)
    {
        $start = $get('start_date');
        $end = $get('end_date');

        if ($start && $end) {
            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);

            $days = $startDate->diffInDays($endDate) + 1;

            $set('days', $days);
        }
    }
    protected static function updateRemainingDays(Get $get, Set $set): void
    {
        $leaveTypeId = $get('leave_type_id');
        $userId      = Auth::id(); // or Auth::guard('employee')->id()

        if (! $leaveTypeId || ! $userId) {
            $set('remaining_days', null);
            return;
        }

        $year = now()->year;

        // 1) Total days for this leave type per year
        $totalPerYear = LeaveType::find($leaveTypeId)?->days_per_year ?? 0;

        // 2) Days already taken this year (approved only)
        $taken = LeaveRequest::query()
            ->where('user_id', $userId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('status', 'approved')
            ->whereYear('start_date', $year)
            ->sum('days');

        // 3) Remaining
        $remaining = max($totalPerYear - $taken, 0);

        $set('remaining_days', $remaining);
    }
}
