<?php

namespace App\Filament\Admin\Resources\LeaveRequests\Tables;

use App\Models\Attendance;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Requester name
                TextColumn::make('user.name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),

                // Leave type name
                TextColumn::make('leaveType.name')
                    ->label('Leave type')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('days')
                    ->label('Days')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger'  => 'rejected',
                    ])
                    ->sortable(),

                TextColumn::make('approver.name')
                    ->label('Approved by')
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('approved_at')
                    ->label('Approved at')
                    ->dateTime()
                    ->toggleable(),
            ])
            ->filters([
                // you can add status filters here later
            ])
            ->recordActions([
                // Just view the request
                ViewAction::make(),

                // Approve button
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        // 1) Update leave request status
                        $record->update([
                            'status'      => 'approved',
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);

                        // 2) Convert user -> employee
                        $employee = $record->user?->employee;

                        if (! $employee) {
                            return; // no employee profile, nothing to create
                        }

                        $start = Carbon::parse($record->start_date)->startOfDay();
                        $end   = Carbon::parse($record->end_date)->startOfDay();

                        // 3) For each day in the range, create leave attendance (morning & afternoon)
                        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {

                            foreach (['morning', 'afternoon'] as $shift) {
                                Attendance::firstOrCreate(
                                    [
                                        'employee_id' => $employee->id,
                                        'date'        => $date->toDateString(),
                                        'shift'       => $shift,
                                    ],
                                    [
                                        'status'           => 'leave',
                                        'leave_request_id' => $record->id,
                                        'check_in'         => null,
                                        'check_out'        => null,
                                        'notes'            => null,
                                    ],
                                );
                            }
                        }
                    }),
                // Reject button
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        // you can add a Textarea for rejection_reason later if you want
                    ])
                    ->visible(fn($record) => $record->status === 'pending')
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status'           => 'rejected',
                            'approved_by'      => Auth::id(),
                            'approved_at'      => now(),
                            'rejection_reason' => $data['rejection_reason'] ?? null,
                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
