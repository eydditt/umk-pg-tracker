<?php

namespace App\Filament\Widgets;

use App\Models\StudentProgress;
use Filament\Widgets\ChartWidget;

class ProgressMilestonesChart extends ChartWidget
{
    protected static ?int $sort = 7;
    protected ?string $heading = 'Progress Milestones Overview';
    protected ?string $maxHeight = '300px';
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $completed = fn($col) => StudentProgress::where($col, 'Completed')->count();

        $milestones = [
            'English Test'    => $completed('eng_test_status'),
            'Pre-Viva'        => $completed('pre_viva_status'),
            'Viva'            => $completed('viva_status'),
            'Degree Verified' => $completed('degree_verification_status'),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Completed',
                    'data' => array_values($milestones),
                    'backgroundColor' => ['#2A9D8F', '#7C3AED', '#E9C46A', '#E76F51'],
                ],
            ],
            'labels' => array_keys($milestones),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
