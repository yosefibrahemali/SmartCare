<?php

namespace App\Filament\Widgets;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class DashboardNotificationWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-notification-widget';

    protected static $pollingInterval = 2;

    public function mount(): void
    {
        $dataFile = storage_path('app/patient_data.json');

        if (!file_exists($dataFile)) {
            return;
        }

        $data = json_decode(file_get_contents($dataFile), true);

        $heartRate = $data['heart_rate'] ?? 0;
        $bloodPressure = $data['blood_pressure'] ?? '0/0';
        $battery = $data['battery'] ?? 0;

        // ===== معدل ضربات القلب =====
        if ($heartRate > 120) {
            Notification::make()
                ->title('معدل ضربات القلب مرتفع!')
                ->danger()
                ->body("معدل ضربات القلب الحالي: {$heartRate} نبضة/دقيقة — يحتاج المريض إلى راحة.")
                ->send();
        } elseif ($heartRate < 60) {
            Notification::make()
                ->title('معدل ضربات القلب منخفض!')
                ->warning()
                ->body("معدل ضربات القلب الحالي: {$heartRate} نبضة/دقيقة — يُفضل مراجعة الطبيب.")
                ->send();
        } else {
            Notification::make()
                ->title('معدل ضربات القلب طبيعي')
                ->success()
                ->body("معدل ضربات القلب الحالي: {$heartRate} نبضة/دقيقة")
                ->send();
        }

        // ===== ضغط الدم =====
        // مثال: 120/80
        [$systolic, $diastolic] = explode('/', $bloodPressure . '/');
        $systolic = (int) $systolic;
        $diastolic = (int) $diastolic;

        if ($systolic > 140 || $diastolic > 90) {
            Notification::make()
                ->title('ضغط الدم مرتفع!')
                ->danger()
                ->body("قراءة الضغط: {$bloodPressure} — يُنصح بالراحة أو مراجعة الطبيب.")
                ->send();
        } elseif ($systolic < 90 || $diastolic < 60) {
            Notification::make()
                ->title('ضغط الدم منخفض!')
                ->warning()
                ->body("قراءة الضغط: {$bloodPressure} — يُفضل شرب الماء أو مراجعة الطبيب.")
                ->send();
        } else {
            Notification::make()
                ->title('ضغط الدم طبيعي')
                ->success()
                ->body("قراءة الضغط: {$bloodPressure}")
                ->send();
        }

        // ===== نسبة الشحن =====
        if ($battery < 20) {
            Notification::make()
                ->title('🔋 البطارية منخفضة!')
                ->warning()
                ->body("نسبة الشحن الحالية: {$battery}% — يرجى شحن الساعة لتفادي التوقف.")
                ->send();
        } elseif ($battery >= 90) {
            Notification::make()
                ->title('⚡ البطارية ممتلئة تقريبًا')
                ->success()
                ->body("نسبة الشحن الحالية: {$battery}% — يمكنك استخدامها براحة.")
                ->send();
        }
        //  Notification::make()
        //     ->title('Saved successfully')
        //     ->success()
        //     ->send();
    }
}
