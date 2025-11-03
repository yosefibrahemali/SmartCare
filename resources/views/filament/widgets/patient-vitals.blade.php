<x-filament::widget>
    <x-filament::card class="space-y-4">
        <h2 class="text-lg font-bold mb-2">Patient Vitals</h2>

        <div class="flex items-center space-x-2">
            <x-heroicon-o-heart class="w-6 h-6 text-red-500" />
            <span><strong>Heart Rate:</strong> {{ $heartRate }} bpm</span>
        </div>

        <div class="flex items-center space-x-2">
            <x-heroicon-o-adjustments class="w-6 h-6 text-blue-500" />
            <span><strong>Blood Pressure:</strong> {{ $bloodPressure }}</span>
        </div>

        <div class="flex items-center space-x-2">
            <x-heroicon-o-battery class="w-6 h-6 text-green-500" />
            <span><strong>Battery:</strong> {{ $battery }}%</span>
        </div>
    </x-filament::card>
</x-filament::widget>
