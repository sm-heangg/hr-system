<?php

namespace App\Filament\Employee\Resources\Attendances\Pages;

use App\Filament\Employee\Resources\Attendances\AttendanceResource;
use App\Filament\Employee\Resources\Attendances\Pages\ScanCheckOut;
use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ScanCheckIn extends Page
{
    protected static string $resource = AttendanceResource::class;

    protected static ?string $navigationLabel = 'QR Check-in';

    // Single token both pages will accept (you can put this plain text in the QR)
    protected const VALID_PAYLOAD = 'attendance-main-office';

    public function getView(): string
    {
        return 'filament.employee.attendance.scan-check-in';
    }

    public function handleScan(string $payload): void
    {
        // ---- 1) Normalize & debug QR payload ----
        $payload = trim($payload);

        // Debug: see exactly what the QR text is.
        Notification::make()
            ->title('QR Payload (Check-in)')
            ->body('[' . $payload . ']')
            ->info()
            ->send();

        // Accept if it *contains* our token anywhere
        if (! str_contains($payload, self::VALID_PAYLOAD)) {
            Notification::make()
                ->title('Invalid QR Code')
                ->body('Please scan the official attendance QR code at the office.')
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
                ->title('No Employee Profile')
                ->body('Your account is not attached to an employee profile. Please contact HR.')
                ->danger()
                ->send();

            return;
        }

        $employeeId = $employee->id;
        $today      = now()->toDateString();
        $now        = now();
        $timeStr    = $now->format('H:i');      // keep string for some comparisons
        $nowTime    = Carbon::createFromFormat('H:i', $timeStr);

        // Block new check-ins if afternoon shift is already finished today
        $finishedAfternoon = Attendance::query()
            ->where('employee_id', $employeeId)
            ->where('date', $today)
            ->where('shift', 'afternoon')
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->exists();

        if ($finishedAfternoon) {
            Notification::make()
                ->title('Day already finished')
                ->body('You have already completed all your shifts for today.')
                ->warning()
                ->send();

            return;
        }

        // ---- 3) Employee shift times (Carbon objects) ----
        $morningStart   = $employee->shift_morning_start
            ? Carbon::parse($employee->shift_morning_start)
            : null;

        $morningEnd     = $employee->shift_morning_end
            ? Carbon::parse($employee->shift_morning_end)
            : null;

        $afternoonStart = $employee->shift_afternoon_start
            ? Carbon::parse($employee->shift_afternoon_start)
            : null;

        $afternoonEnd   = $employee->shift_afternoon_end
            ? Carbon::parse($employee->shift_afternoon_end)
            : null;

        // ---- 4) Determine which shift this scan belongs to ----
        // Allow employee to check in up to 60 minutes early
        $earlyMinutes = 60;

        $shift = null;

        if ($morningStart && $morningEnd) {
            $morningWindowStart = $morningStart->copy()->subMinutes($earlyMinutes);
            $morningWindowEnd   = $morningEnd; // you can also ->addMinutes(10) if you want

            if ($nowTime->between($morningWindowStart, $morningWindowEnd, true)) {
                $shift = 'morning';
            }
        }

        if (! $shift && $afternoonStart && $afternoonEnd) {
            $afternoonWindowStart = $afternoonStart->copy()->subMinutes($earlyMinutes);
            $afternoonWindowEnd   = $afternoonEnd;

            if ($nowTime->between($afternoonWindowStart, $afternoonWindowEnd, true)) {
                $shift = 'afternoon';
            }
        }

        if (! $shift) {
            Notification::make()
                ->title('Outside Shift Time')
                ->body('You are not scanning within your assigned shift hours.')
                ->warning()
                ->send();

            return;
        }

        // ---- 5) If scanning afternoon → auto-mark morning ABSENT if they missed it ----
        if (
            $shift === 'afternoon'
            && $employee->shift_morning_start
            && $employee->shift_morning_end
        ) {
            $morningEndStr = Carbon::parse($employee->shift_morning_end)->format('H:i');

            // only mark absent if this scan is AFTER the morning end time
            if ($timeStr > $morningEndStr) {
                $hasMorningRecord = Attendance::where('employee_id', $employeeId)
                    ->where('date', $today)
                    ->where('shift', 'morning')
                    ->exists();

                if (! $hasMorningRecord) {
                    Attendance::create([
                        'employee_id' => $employeeId,
                        'date'        => $today,
                        'shift'       => 'morning',
                        'status'      => 'absent',
                    ]);
                }
            }
        }

        // ---- 6) Prevent double check-in for this shift ----
        $attendance = Attendance::where('employee_id', $employeeId)
            ->where('date', $today)
            ->where('shift', $shift)
            ->first();

        if ($attendance && $attendance->check_in) {
            Notification::make()
                ->title('Already Checked In')
                ->body("You have already checked in for your {$shift} shift today.")
                ->warning()
                ->send();

            return;
        }

        // ---- 7) Determine Present / Late ----
        $shiftStartStr = $shift === 'morning'
            ? $morningStart?->format('H:i')
            : $afternoonStart?->format('H:i');

        // early or on time = present, after start = late
        $status = $shiftStartStr && $timeStr > $shiftStartStr ? 'late' : 'present';

        // ---- 8) Record attendance ----
        Attendance::updateOrCreate(
            [
                'employee_id' => $employeeId,
                'date'        => $today,
                'shift'       => $shift,
            ],
            [
                'check_in' => $now,
                'status'   => $status,
            ]
        );

        Notification::make()
            ->title("Checked In ({$shift} shift)")
            ->body("Your {$shift} shift check-in has been recorded.")
            ->success()
            ->send();

        // ---- 9) Redirect to check-out page ----
        $this->redirect(ScanCheckOut::getUrl());
    }
}
