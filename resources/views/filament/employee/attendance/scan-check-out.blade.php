<x-filament-panels::page>
    <div class="space-y-4">
        <h2 class="text-lg font-semibold">
            Scan QR to Check Out
        </h2>

        <div
            x-data="{ scanning: false }"
            x-init="
                const onScanSuccess = (decodedText) => {
                    if (scanning) return;
                    scanning = true;

                    navigator.vibrate?.(150);

                    $wire.handleScan(decodedText)
                        .then(() => {
                            setTimeout(() => scanning = false, 1500);
                        });
                };

                const onScanError = (err) => console.warn(err);

                const scanner = new Html5QrcodeScanner('qr-reader', {
                    fps: 10,
                    qrbox: 200,
                });

                scanner.render(onScanSuccess, onScanError);
            "
        >
            <div id='qr-reader'
                class="w-full max-w-md mx-auto border rounded-md shadow">
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</x-filament-panels::page>
