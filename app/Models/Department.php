<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Department extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description'
    ];
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
}
