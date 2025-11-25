<div
    x-data="{ time: '{{ now()->format('H:i:s') }}' }"
    x-init="
        setInterval(() => {
            const d = new Date();
            time = d.toLocaleTimeString('en-GB', { hour12: false });
        }, 1000);
    "
    class="text-center text-3xl font-mono"
>
    <span x-text="time"></span>
</div>
