<?php

namespace App\Filament\Admin\Resources\LeaveTypes\Pages;

use App\Filament\Admin\Resources\LeaveTypes\LeaveTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveType extends CreateRecord
{
    protected static string $resource = LeaveTypeResource::class;
}
