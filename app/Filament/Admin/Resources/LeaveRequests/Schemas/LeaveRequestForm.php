<?php

namespace App\Filament\Admin\Resources\LeaveRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // EMPLOYEE
            Select::make('user_id')
                ->label('Employee')
                ->relationship('user', 'name')
                ->preload()
                ->searchable()
                ->required(),

            // LEAVE TYPE
            Select::make('leave_type_id')
                ->label('Leave Type')
                ->relationship('leaveType', 'name')
                ->preload()
                ->searchable()
                ->required(),

            // START DATE
            DatePicker::make('start_date')
                ->label('Start Date')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $start = $state;
                    $end   = $get('end_date');

                    if ($start && $end) {
                        $days = Carbon::parse($start)
                            ->diffInDays(Carbon::parse($end)) + 1;

                        $set('days', $days);
                    } else {
                        $set('days', null);
                    }
                }),

            // END DATE
            DatePicker::make('end_date')
                ->label('End Date')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $start = $get('start_date');
                    $end   = $state;

                    if ($start && $end) {
                        $days = Carbon::parse($start)
                            ->diffInDays(Carbon::parse($end)) + 1;

                        $set('days', $days);
                    } else {
                        $set('days', null);
                    }
                }),

            // DAYS (auto calculated)
            TextInput::make('days')
                ->label('Total Days')
                ->numeric()
                ->disabled()   // user cannot edit, only see
                ->dehydrated(), // still saved to DB
            // no need for afterStateHydrated now

            // REASON
            Textarea::make('reason')
                ->label('Reason')
                ->columnSpanFull()
                ->required(),

            // STATUS (admin only)
            ToggleButtons::make('status')
                ->label('Status')
                ->options([
                    'approved' => 'Approved',
                ])
                ->colors([
                    'approved' => 'success',
                ])
                ->visible(fn() => Auth::user()?->hasRole('super_admin'))
                ->required(),

            // APPROVED BY (admin only)
            TextInput::make('approved_by')
                ->label('Approved By')
                ->readOnly()
                ->visible(fn($record) => $record && $record->status !== 'pending')
                ->dehydrated(false),

            // APPROVED AT (admin only)
            TextInput::make('approved_at')
                ->label('Approved At')
                ->readOnly()
                ->visible(fn($record) => $record && $record->status !== 'pending')
                ->dehydrated(false),

            // REJECTION REASON
            Textarea::make('rejection_reason')
                ->label('Rejection Reason')
                ->visible(fn($get) => $get('status') === 'rejected')
                ->columnSpanFull(),
        ]);
    }
}
