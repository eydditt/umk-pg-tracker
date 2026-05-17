<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IntakeTrendChart extends ChartWidget
{
    protected static ?int $sort = 4;
    protected ?string $heading = 'Student Intake Trend';
    protected ?string $maxHeight = '300px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Student::select('intake_session', DB::raw('count(*) as total'))
            ->groupBy('intake_session')
            ->orderBy('intake_session')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Students per Intake',
                    'data' => $data->pluck('total')->toArray(),
                    'borderColor' => '#2A9D8F',
                    'backgroundColor' => 'rgba(42,157,143,0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->pluck('intake_session')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
