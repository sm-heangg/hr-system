<?php

namespace App\Filament\Admin\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class AttendanceQr extends Page
{
    // optional icon
    // protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationLabel = 'Attendance QR';
    protected static string | UnitEnum | null $navigationGroup = 'Attendance QR';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $title = 'Attendance QR for Employees';

    /**
     * Payload that will be encoded in the QR.
     * Must match ScanCheckIn::VALID_PAYLOAD on employee side.
     */
    public string $payload;

    public function mount(): void
    {
        $this->payload = 'attendance-main-office';
    }

   // redirect to view on blade view
    public function getView(): string
    {
        return 'filament.admin.pages.attendance-qr';
    }
}
