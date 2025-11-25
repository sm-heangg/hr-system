<x-filament-panels::page>
    <div class="space-y-4">
        <h2 class="text-lg font-semibold">
            Scan QR to Check In
        </h2>

        {{-- IMPORTANT: wire:ignore so Livewire doesn't re-mount the scanner --}}
        <div
            wire:ignore
            x-data="{ scanning: false, scannerInstance: null }"
            x-init="
                const onScanSuccess = (decodedText) => {
                    // Prevent multiple triggers
                    if (scanning) return;
                    scanning = true;

                    navigator.vibrate?.(150);

                    $wire.handleScan(decodedText)
                        .then(() => {
                            // Stop the camera / scanner after first scan
                            if (scannerInstance) {
                                scannerInstance.clear().catch(() => {});
                            }
                            // If you want to allow another scan later, you can set:
                            // scanning = false;
                        });
                };

                const onScanError = (err) => {
                    console.warn(err);
                };

                // Create scanner
                scannerInstance = new Html5QrcodeScanner('qr-reader', {
                    fps: 10,
                    qrbox: 200,
                });

                scannerInstance.render(onScanSuccess, onScanError);
            "
        >
            <div
                id="qr-reader"
                class="w-full max-w-md mx-auto border rounded-md shadow"
            ></div>
        </div>
    </div>

    {{-- Include QR scanner library --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</x-filament-panels::page>
