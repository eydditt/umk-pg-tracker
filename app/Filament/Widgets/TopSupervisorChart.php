<?php

namespace App\Filament\Widgets;

use App\Models\Lecturer;
use Filament\Widgets\ChartWidget;

class TopSupervisorChart extends ChartWidget
{
    protected static ?int $sort = 8;
    protected int | string | array $columnSpan = 2;
    protected ?string $heading = 'Top 10 Supervisors (Current Student Supervision)';

    protected function getData(): array
    {
        $lecturers = Lecturer::withCount(['mainStudents' => function($query) {
                $query->where('status', 'Active');
            }])
            ->orderByDesc('main_students_count')
            ->limit(10)
            ->get();

        return [
            'datasets' => [[
                'label' => 'Current Active Students Supervised',
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
            'maintainAspectRatio' => false,
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}