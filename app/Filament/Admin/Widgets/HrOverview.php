<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\LeaveRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HrOverview extends StatsOverviewWidget
{
    // DO NOT REDECLARE $pollingInterval
    // Filament v4 will throw a fatal error

    protected function getStats(): array
    {
        return [

            Stat::make('Employees', Employee::count())
                ->description('Total registered employees')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Departments', Department::count())
                ->description('All departments')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('success'),

            Stat::make('Positions', Position::count())
                ->description('Job positions available')
                ->descriptionIcon('heroicon-o-identification')
                ->color('info'),

            Stat::make('Pending Leave Requests', LeaveRequest::where('status', 'pending')->count())
                ->description('Waiting for approval')
                ->descriptionIcon('heroicon-o-arrow-top-right-on-square')
                ->color('warning'),
        ];
    }
}
