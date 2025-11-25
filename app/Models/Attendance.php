<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = [
        'employee_id',
        'date',
        'shift',             // 'morning' | 'afternoon'
        'check_in',
        'check_out',
        'status',            // 'present' | 'absent' | 'late' | 'leave'
        'notes',
        'leave_request_id',  // link to approved leave (if any)
    ];

    protected $casts = [
        'date' => 'date',
        // check_in/check_out are TIME columns, they come back as strings like "07:30:00"
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveRequest(): BelongsTo
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    /**
     * Shift start time (Carbon) based on this record's shift.
     */
    protected function getShiftStart(): ?Carbon
    {
        if (! $this->employee) {
            return null;
        }

        $shiftStartTime = match ($this->shift) {
            'morning'   => $this->employee->shift_morning_start,
            'afternoon' => $this->employee->shift_afternoon_start,
            default     => null,
        };

        if (! $shiftStartTime) {
            return null;
        }

        return Carbon::parse($shiftStartTime);
    }

    /**
     * How many minutes/hours late this check-in is.
     * Returns a string like "40m", "1h 5m", or null if on time/early.
     */
    public function getLateByAttribute(): ?string
    {
        if (! $this->check_in) {
            return null;
        }

        $shiftStart = $this->getShiftStart();
        if (! $shiftStart) {
            return null;
        }

        $checkIn = Carbon::parse($this->check_in);

        // On time or early → not late
        if ($checkIn->lessThanOrEqualTo($shiftStart)) {
            return null;
        }

        $minutesLate = $shiftStart->diffInMinutes($checkIn);

        $hours = intdiv($minutesLate, 60);
        $mins  = $minutesLate % 60;

        if ($hours && $mins) {
            return "{$hours}h {$mins}m";
        }

        if ($hours) {
            return "{$hours}h";
        }

        return "{$mins}m";
    }

    /**
     * How many minutes/hours early this check-in is.
     * Returns a string like "15m", "1h 10m", or null if on time/late.
     */
    public function getEarlyByAttribute(): ?string
    {
        if (! $this->check_in) {
            return null;
        }

        $shiftStart = $this->getShiftStart();
        if (! $shiftStart) {
            return null;
        }

        $checkIn = Carbon::parse($this->check_in);

        // On time or late → not early
        if ($checkIn->greaterThanOrEqualTo($shiftStart)) {
            return null;
        }

        $minutesEarly = $checkIn->diffInMinutes($shiftStart);

        $hours = intdiv($minutesEarly, 60);
        $mins  = $minutesEarly % 60;

        if ($hours && $mins) {
            return "{$hours}h {$mins}m";
        }

        if ($hours) {
            return "{$hours}h";
        }

        return "{$mins}m";
    }
}
