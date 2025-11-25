<?php

namespace App\Models;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'days',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'approved_at'  => 'datetime',
    ];

    /* ----------------- Relationships ----------------- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /* ----------------- Model Events ----------------- */

    protected static function booted(): void
    {
        // Before saving: if status is approved and approver is empty,
        // auto fill approved_by and approved_at
        static::saving(function (LeaveRequest $leave) {
            if ($leave->status === 'approved' && ! $leave->approved_by) {
                $leave->approved_by = Auth::id();
                $leave->approved_at = now();
            }
        });

        // After saving: if status JUST changed to approved,
        // create attendance records for each day of leave.
        static::saved(function (LeaveRequest $leave) {
            $originalStatus = $leave->getOriginal('status');

            if ($originalStatus !== 'approved' && $leave->status === 'approved') {
                $leave->createLeaveAttendances();
            }
        });
    }

    /* ----------------- Helper to create Attendance ----------------- */

    public function createLeaveAttendances(): void
    {
        // Get employee from user
        $employee = $this->user?->employee;

        if (! $employee) {
            return; // no employee profile → nothing to create
        }

        $start = Carbon::parse($this->start_date)->startOfDay();
        $end   = Carbon::parse($this->end_date)->startOfDay();

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {

            // Full-day leave → mark both shifts as leave
            foreach (['morning', 'afternoon'] as $shift) {
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'date'        => $date->toDateString(),
                        'shift'       => $shift,
                    ],
                    [
                        'status'           => 'leave',
                        'leave_request_id' => $this->id,
                        'notes'            => $this->reason,
                    ]
                );
            }
        }
    }
}
