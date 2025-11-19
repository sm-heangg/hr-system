<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'notes',
        'check_in',
        'check_out'
    ];
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'check_in' => 'datetime',
            'check_out' => 'datetime'
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
