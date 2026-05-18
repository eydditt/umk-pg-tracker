<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IntakeTrendChart extends ChartWidget
{
    protected static ?int $sort = 9;
    protected ?string $heading = 'Student Intake Trend';
    protected ?string $maxHeight = '300px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Student::select(
                'intake_session',
                DB::raw("SUM(CASE WHEN program_type = 'Master' THEN 1 ELSE 0 END) as master_total"),
                DB::raw("SUM(CASE WHEN program_type = 'PhD' THEN 1 ELSE 0 END) as phd_total")
            )
            ->groupBy('intake_session')
            ->orderBy('intake_session')
            ->get();

        $labels = $data->pluck('intake_session')->toArray();
        $masterData = $data->pluck('master_total')->toArray();
        $phdData = $data->pluck('phd_total')->toArray();

        array_unshift($labels, ''); 
        array_unshift($masterData, 0);
        array_unshift($phdData, 0);

        return [
            'datasets' => [
                [
                    'label' => 'Master Students',
                    'data' => $masterData,
                    'borderColor' => '#2A9D8F',
                    'backgroundColor' => 'rgba(42,157,143,0.2)',
                    'fill' => false,
                    'tension' => 0.2 , 
                ],
                [
                    'label' => 'PhD Students',
                    'data' => $phdData,
                    'borderColor' => '#7C3AED',
                    'backgroundColor' => 'rgba(124,58,237,0.2)',
                    'fill' => false, 
                    'tension' => 0.2 , 
                ],
            ],
            'labels' => $labels, 
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0, 
                    ],
                ],
            ],
        ];
    }
}