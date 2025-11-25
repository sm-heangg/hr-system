<?php

namespace App\Filament\Employee\Resources\Attendances\Pages;

use App\Filament\Employee\Resources\Attendances\AttendanceResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListMyAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    /** Only this employee’s data */
    protected function getTableQuery(): Builder
    {
        $employee = Auth::guard('employee')->user()?->employee;

        if (! $employee) {
            return parent::getTableQuery()->whereRaw('1 = 0');
        }

        return parent::getTableQuery()
            ->where('employee_id', $employee->id);
    }

    /** Remove Create button */
    protected function getHeaderActions(): array
    {
        return [];
    }

    /** Make table read-only: no row actions, no bulk, no row click */
    public function table(Tables\Table $table): Tables\Table
    {
        return AttendanceResource::table($table)
            ->actions([])
            ->bulkActions([])
            ->recordUrl(null)
            ->recordAction(null);
    }
}
