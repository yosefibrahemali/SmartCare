<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PatientVitalsStats extends StatsOverviewWidget
{

   
  //  protected static ?int|string|bool $pollingInterval = 5; // تحديث كل 5 ثواني

    protected function getHeading(): ?string
    {
        return 'التحليلات الصحية';
    }

    protected function getStats(): array
    {
        $dataFile = storage_path('app/patient_data.json');

        $heartRate = 'N/A';
        $bloodPressure = 'N/A';
        $battery = 'N/A';

        if (file_exists($dataFile)) {
            $data = json_decode(file_get_contents($dataFile), true);
            $heartRate = $data['heart_rate'] ?? 'N/A';
            $bloodPressure = $data['blood_pressure'] ?? 'N/A';
            $battery = $data['battery'] ?? 'N/A';
        }

        // ✅ تقييم معدل ضربات القلب
        if ($heartRate !== 'N/A') {
            if ($heartRate < 60) {
                $hrStatus = 'منخفض';
                $hrColor = 'warning';
                $hrIcon = 'heroicon-o-arrow-down';
            } elseif ($heartRate <= 100) {
                $hrStatus = 'طبيعي';
                $hrColor = 'success';
                $hrIcon = 'heroicon-o-check-circle';
            } else {
                $hrStatus = 'مرتفع';
                $hrColor = 'danger';
                $hrIcon = 'heroicon-o-arrow-up';
            }
        } else {
            $hrStatus = 'غير متاح';
            $hrColor = 'gray';
            $hrIcon = 'heroicon-o-exclamation-circle';
        }

        // ✅ تقييم ضغط الدم
        if ($bloodPressure !== 'N/A' && str_contains($bloodPressure, '/')) {
            [$sys, $dia] = explode('/', $bloodPressure);
            $sys = (int) $sys;
            $dia = (int) $dia;
            if ($sys < 90 || $dia < 60) {
                $bpStatus = 'منخفض';
                $bpColor = 'warning';
                $bpIcon = 'heroicon-o-arrow-down';
            } elseif ($sys <= 120 && $dia <= 80) {
                $bpStatus = 'طبيعي';
                $bpColor = 'success';
                $bpIcon = 'heroicon-o-check-circle';
            } else {
                $bpStatus = 'مرتفع';
                $bpColor = 'danger';
                $bpIcon = 'heroicon-o-arrow-up';
            }
        } else {
            $bpStatus = 'غير متاح';
            $bpColor = 'gray';
            $bpIcon = 'heroicon-o-exclamation-circle';
        }

        // ✅ تقييم البطارية
        $batteryColor = $battery < 30 ? 'danger' : ($battery < 60 ? 'warning' : 'success');
        $batteryIcon = $battery < 30 ? 'heroicon-o-battery-0' : ($battery < 60 ? 'heroicon-o-battery-50' : 'heroicon-o-battery-100');

        return [
            Stat::make('معدل ضربات القلب', "{$heartRate} نبضة/د")
                ->icon('heroicon-o-heart')
                ->description("{$hrStatus}")
                ->descriptionIcon($hrIcon)
                ->chart([55, 72, 95, 88, 100, 92, 78])
                ->color($hrColor),

            Stat::make('ضغط الدم', $bloodPressure)
                ->icon('heroicon-o-beaker')
                ->description("{$bpStatus}")
                ->descriptionIcon($bpIcon)
                ->chart([110, 115, 118, 121, 117, 119])
                ->color($bpColor),

            Stat::make('شحن البطارية', "{$battery}%")
                ->icon($batteryIcon)
                ->description('آخر تحديث ' . now()->format('H:i'))
                ->descriptionIcon('heroicon-o-clock')
                ->chart([$battery - 10, $battery - 5, $battery])
                ->color($batteryColor),
        ];
    }

}
