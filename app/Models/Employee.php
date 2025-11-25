<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Model
{
    use HasRoles;
    protected $guard_name = 'employee';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'employee_code',
        'department_id',
        'position_id',
        'hire_date',
        'salary',
        'status',
        'shift_morning_start',
        'shift_morning_end',
        'shift_afternoon_start',
        'shift_afternoon_end',
    ];
    protected $timestamp = false;

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
    protected static function booted()
    {
        static::creating(function ($employee) {

            // If employee_code already manually provided, skip
            if ($employee->employee_code) {
                return;
            }

            // Get last employee code (EMP001, EMP002 ...)
            $lastEmployee = Employee::orderBy('id', 'desc')->first();

            if (!$lastEmployee || !$lastEmployee->employee_code) {
                // First employee
                $nextNumber = 1;
            } else {
                // Extract number from EMPxxx
                $number = intval(substr($lastEmployee->employee_code, 3));
                $nextNumber = $number + 1;
            }

            // Generate new code EMP001
            $employee->employee_code = 'EMP' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }
    // App\Models\Employee.php

    public static function nextCode(): string
    {
        $last = self::orderBy('id', 'desc')->first();

        if (! $last || ! $last->employee_code) {
            $nextNumber = 1;
        } else {
            $number = (int) substr($last->employee_code, 3); // remove "EMP"
            $nextNumber = $number + 1;
        }

        // EMP001, EMP002, ...
        return 'EMP' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
