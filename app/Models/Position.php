<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
    ];

    // Disable timestamps if you don't have created_at / updated_at
    public $timestamps = false;

    // Usually: one position -> many employees
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
