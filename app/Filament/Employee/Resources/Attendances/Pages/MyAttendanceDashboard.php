<?php

namespace App\Filament\Employee\Resources\Attendances\Pages;

use App\Filament\Employee\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class MyAttendanceDashboard extends Page
{
    protected static string $resource = AttendanceResource::class;

    protected static ?string $navigationLabel = 'My Attendance'; // sidebar label
    public function getView(): string
    {
        return 'filament.employee.attendance.dashboard';
    }

    public function getEmployee()
    {
        return Auth::guard('employee')->user()?->employee;
    }

    public function getTodayOpenAttendance()
    {
        $employee = $this->getEmployee();

        if (! $employee) {
            return null;
        }

        return Attendance::query()
            ->where('employee_id', $employee->id)
            ->where('date', now()->toDateString())
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->first();
    }

    public function showCheckInButton(): bool
    {
        // If afternoon shift completed → no more check-ins today
        if ($this->isDayFinished()) {
            return false;
        }

        // No open record → can check in
        return $this->getTodayOpenAttendance() === null;
    }

    public function showCheckOutButton(): bool
    {
        // Have a record with check_in but no check_out → can check out
        return $this->getTodayOpenAttendance() !== null;
    }
    public function isDayFinished(): bool
    {
        $employee = $this->getEmployee();

        if (! $employee) {
            return false;
        }

        // Day is finished when afternoon shift is fully completed
        return \App\Models\Attendance::query()
            ->where('employee_id', $employee->id)
            ->where('date', now()->toDateString())
            ->where('shift', 'afternoon')
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->exists();
    }


    public function getCheckInUrl(): string
    {
        return ScanCheckIn::getUrl();
    }

    public function getCheckOutUrl(): string
    {
        return ScanCheckOut::getUrl();
    }

    public function getHistoryUrl(): string
    {
        return ListMyAttendances::getUrl();
    }
}
