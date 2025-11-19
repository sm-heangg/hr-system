<?php

namespace App\Filament\Employee\Resources\LeaveRequests\Schemas;

use Filament\Schemas\Schema;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }
}
