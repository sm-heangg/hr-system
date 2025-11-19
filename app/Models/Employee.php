<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'employee_code',
        'department_id',
        'position_id',
        'hire_date',
        'salary',
        'status'
    ];
    protected $timestamp = false;

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
