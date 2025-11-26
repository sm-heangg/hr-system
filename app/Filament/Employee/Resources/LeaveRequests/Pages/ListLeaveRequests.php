<?php

namespace App\Filament\Employee\Resources\LeaveRequests\Pages;

use App\Filament\Employee\Resources\LeaveRequests\LeaveRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListLeaveRequests extends ListRecords
{
    protected static string $resource = LeaveRequestResource::class;

    /**
     * Only show the logged-in employee's leave requests.
     */
    public function table(Table $table): Table
    {
        return parent::table($table)->modifyQueryUsing(function (Builder $query) {
            // 👇 Use the correct guard for your employee panel
            $user = Auth::guard('employee')->user();   // or ->guard('web') if you use web

            if ($user) {
                $query->where('user_id', $user->id);
            } else {
                // No user → return empty result
                $query->whereRaw('1 = 0');
            }
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
