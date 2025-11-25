<?php

namespace App\Filament\Employee\Resources\Attendances\Pages;

use App\Filament\Employee\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ScanCheckOut extends Page
{
    protected static string $resource = AttendanceResource::class;

    protected static ?string $navigationLabel = 'QR Check-out';

    // Same token as check-in → one QR works for both
    protected const VALID_PAYLOAD = 'attendance-main-office';

    public function getView(): string
    {
        return 'filament.employee.attendance.scan-check-out';
    }

    public function handleScan(string $payload): void
    {
        // ---- 1) Normalize & debug QR payload ----
        $payload = trim($payload);

        Notification::make()
            ->title('QR Payload (Check-out)')
            ->body('[' . $payload . ']')
            ->info()
            ->send();

        if (! str_contains($payload, self::VALID_PAYLOAD)) {
            Notification::make()
                ->title('Invalid QR Code')
                ->body('Please scan the official check-out QR code.')
                ->danger()
                ->send();

            return;
        }

        // ---- 2) Auth → User → Employee ----
        $user = Auth::guard('employee')->user();

        if (! $user) {
            Notification::make()
                ->title('Not Logged In')
                ->body('Please log in again.')
                ->danger()
                ->send();

            return;
        }

        $employee = $user->employee;

        if (! $employee) {
            Notification::make()
                ->title('No employee profile')
                ->body('Your account is not attached to any employee record.')
                ->danger()
                ->send();

            return;
        }

        $employeeId = $employee->id;
        $today      = now()->toDateString();
        $now        = now();
        $time       = $now->format('H:i');

        // ---- 3) Shift times ----
        $morningEnd     = $employee->shift_morning_end
            ? Carbon::parse($employee->shift_morning_end)->format('H:i')
            : null;

        $afternoonStart = $employee->shift_afternoon_start
            ? Carbon::parse($employee->shift_afternoon_start)->format('H:i')
            : null;

        $afternoonEnd   = $employee->shift_afternoon_end
            ? Carbon::parse($employee->shift_afternoon_end)->format('H:i')
            : null;

        // ---- 4) Determine which shift this check-out belongs to ----
        $shift = null;

        // If morning shift exists and it's before afternoon start → morning checkout
        if ($morningEnd && $time <= $morningEnd && (!$afternoonStart || $time < $afternoonStart)) {
            $shift = 'morning';
        }
        // Else if afternoon shift exists → afternoon checkout
        elseif ($afternoonEnd && $time >= ($afternoonStart ?? '00:00') && $time <= $afternoonEnd) {
            $shift = 'afternoon';
        }

        if (! $shift) {
            Notification::make()
                ->title('Outside Check-Out Window')
                ->body('You are not checking out within your configured shift hours.')
                ->warning()
                ->send();

            return;
        }

        // ---- 5) Find today's attendance for this shift ----
        $attendance = Attendance::where('employee_id', $employeeId)
            ->where('date', $today)
            ->where('shift', $shift)
            ->first();

        if (! $attendance || ! $attendance->check_in) {
            Notification::make()
                ->title('Check-In Missing')
                ->body("You must check in for your {$shift} shift before checking out.")
                ->warning()
                ->send();

            return;
        }

        if ($attendance->check_out) {
            Notification::make()
                ->title('Already Checked Out')
                ->body("You already checked out for your {$shift} shift today.")
                ->warning()
                ->send();

            return;
        }

        // ---- 6) Determine status (early leave?) ----
        $shiftEnd = $shift === 'morning' ? $morningEnd : $afternoonEnd;

        // If leaving before scheduled end time → mark as 'leave'
        if ($shiftEnd && $time < $shiftEnd) {
            $attendance->status = 'leave';   // matches enum: present/absent/late/leave
        }

        $attendance->check_out = $now;
        $attendance->save();

        Notification::make()
            ->title("Checked Out ({$shift} shift)")
            ->body("Your {$shift} shift check-out has been recorded.")
            ->success()
            ->send();
    }
}
