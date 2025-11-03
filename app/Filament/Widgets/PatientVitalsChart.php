<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;


class PatientVitalsChart extends ChartWidget
{

    

    protected static ?int $sort = 4;
    protected static bool $isLazy = false;

    protected function getData(): array
    {
        $dataFile = storage_path('app/patient_data.json');

        $dates = [];
        $heartRates = [];
        $bloodPressures = [];

        if (file_exists($dataFile)) {
            $data = json_decode(file_get_contents($dataFile), true);

            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('d M'); // اختصار التاريخ لعرض أفضل
                $dates[] = $date;
                $heartRates[] = rand(65, 120);
                $bloodPressures[] = rand(100, 150);
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'معدل ضربات القلب (bpm)',
                    'data' => $heartRates,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'fill' => true,
                    'tension' => 0.4, // يجعل الخط منحني أكثر
                    'pointRadius' => 4,
                    'pointBackgroundColor' => '#ef4444',
                    'pointHoverRadius' => 6,
                ],
                [
                    'label' => 'ضغط الدم (mmHg)',
                    'data' => $bloodPressures,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 4,
                    'pointBackgroundColor' => '#3b82f6',
                    'pointHoverRadius' => 6,
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): string
    {
        return 'مخطط معدل ضربات القلب وضغط الدم خلال الشهر الماضي';
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    // خيارات إضافية لجعل المخطط أكثر جمالًا
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                    ],
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'interaction' => [
                'mode' => 'nearest',
                'axis' => 'x',
                'intersect' => false,
            ],
            'scales' => [
                'x' => [
                    'grid' => ['display' => false],
                ],
                'y' => [
                    'beginAtZero' => false,
                    'grid' => ['color' => 'rgba(0,0,0,0.05)'],
                ],
            ],
        ];
    }

}

