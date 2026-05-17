<?php

namespace App\Filament\Widgets;

use App\Models\Lecturer;
use Filament\Widgets\ChartWidget;

class TopSupervisorChart extends ChartWidget
{
    protected static ?int $sort = 8;
    protected int | string | array $columnSpan = 2;
    protected ?string $heading = 'Top Supervisors by Student Count';
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $lecturers = Lecturer::withCount('mainStudents')
            ->orderByDesc('main_students_count')
            ->limit(8)
            ->get();

        return [
            'datasets' => [[
                'label' => 'Students Supervised',
                'data' => $lecturers->pluck('main_students_count')->toArray(),
                'backgroundColor' => '#2A9D8F',
                'borderRadius' => 6,
            ]],
            'labels' => $lecturers->pluck('full_name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'x' => [
                    'ticks' => ['stepSize' => 1],
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}