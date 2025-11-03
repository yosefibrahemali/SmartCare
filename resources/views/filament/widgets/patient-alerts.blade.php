<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-bold mb-2">النصائح الطبية</h2>

        @php
            $dataFile = storage_path('app/patient_data.json');

            $heartRate = null;
            $bloodPressure = null;
            $alerts = [];

            if (file_exists($dataFile)) {
                $data = json_decode(file_get_contents($dataFile), true);
                $heartRate = $data['heart_rate'] ?? null;
                $bloodPressure = $data['blood_pressure'] ?? null;
            }

            // نصائح بناءً على النبض
            if ($heartRate !== null) {
                if ($heartRate < 60) {
                    $alerts[] = 'معدل ضربات القلب منخفض، يُنصح بالراحة ومراقبة الحالة.';
                } elseif ($heartRate > 100) {
                    $alerts[] = 'معدل ضربات القلب مرتفع، يُنصح بمراجعة الطبيب.';
                }
            }

            // نصائح بناءً على ضغط الدم
            if ($bloodPressure !== null && str_contains($bloodPressure, '/')) {
                [$sys, $dia] = explode('/', $bloodPressure);
                $sys = (int)$sys;
                $dia = (int)$dia;

                if ($sys > 120 || $dia > 80) {
                    $alerts[] = 'ضغط الدم مرتفع، يُنصح بمراجعة الطبيب وشرب الماء.';
                } elseif ($sys < 90 || $dia < 60) {
                    $alerts[] = 'ضغط الدم منخفض، يُنصح بالراحة وتناول وجبة خفيفة.';
                }
            }

            // إذا لا يوجد مشاكل
            if (empty($alerts)) {
                $alerts[] = 'كل المؤشرات ضمن المستوى الطبيعي.';
            }
        @endphp

        <ul class="space-y-2">
            @foreach ($alerts as $alert)
                <li class="text-sm text-gray-700">{{ $alert }}</li>
            @endforeach
        </ul>
    </x-filament::card>
</x-filament::widget>
