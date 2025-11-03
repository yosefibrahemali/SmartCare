<x-filament-widgets::widget>
    <x-filament::section
        icon="heroicon-o-light-bulb"
        icon-color="success"
        class="bg-green-50 border border-green-300 text-green-900 rounded-lg shadow-sm p-4"
    >
        <p class="text-sm font-medium">
            <strong>نصيحة اليوم:</strong> {{ $dailyAdvice }}
        </p>
    </x-filament::section>
</x-filament-widgets::widget>
