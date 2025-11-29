{{-- Update Dashboard for Employee Attendance Management --}}

<x-filament-panels::page>
    <div class="space-y-6">
        <h2 class="text-xl font-semibold">My Attendance</h2>

        @php
            $employee = $this->getEmployee();
        @endphp

        @if (! $employee)
            <x-filament::section>
                <p>Your account is not linked to an employee profile. Please contact HR.</p>
            </x-filament::section>
        @else
            <x-filament::section>
                <div class="space-y-2">
                    <p><strong>Today:</strong> {{ now()->toDateString() }}</p>

                    <div class="flex flex-wrap gap-3 mt-4">
                        @if ($this->showCheckInButton())
                            <x-filament::button tag="a" color="primary" :href="$this->getCheckInUrl()">
                                Scan to Check In
                            </x-filament::button>
                        @endif

                        @if ($this->showCheckOutButton())
                            <x-filament::button tag="a" color="success" :href="$this->getCheckOutUrl()">
                                Scan to Check Out
                            </x-filament::button>
                        @endif

                        <x-filament::button tag="a" color="gray" :href="$this->getHistoryUrl()">
                            View My Attendance History
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
