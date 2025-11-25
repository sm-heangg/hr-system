<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class MarkAbsentees extends Command
{
    protected $signature = 'attendance:mark-absent';

    protected $description = 'Auto-mark employees as absent if they have no attendance for today';

    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $employees = Employee::where('status', 1)->get(); // active only

        foreach ($employees as $employee) {
            $exists = Attendance::where('employee_id', $employee->id)
                ->where('date', $today)
                ->exists();

            if (! $exists) {
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date'        => $today,
                    'status'      => 'Absent',
                ]);
            }
        }

        $this->info("Absent marking completed for {$today}");
    }
}
