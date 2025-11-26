@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;

    // Generate SVG (no imagick needed)
    $qrSvg = QrCode::format('svg')
        ->size(250)
        ->margin(1)
        ->generate($this->payload);

    // Prepare base64 SVG for download
    $qrDownload = base64_encode($qrSvg);
@endphp
 <!-- scan Qr -->
<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Show SVG QR --}}
        <div class="flex items-center justify-center p-4">
            {!! $qrSvg !!}
        </div>

        {{-- Download Button --}}
        <div class="flex items-center justify-center mt-12">
            <a
                href="data:image/svg+xml;base64,{{ $qrDownload }}"
                download="attendance-qr.svg"
                class=" px-4 py-4 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700"
            >
                Download QR as SVG
            </a>
        </div>
    </div>
</x-filament-panels::page>
