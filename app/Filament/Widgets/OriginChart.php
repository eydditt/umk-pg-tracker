<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;

class OriginChart extends ChartWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 1;
    protected ?string $heading = 'Student Origin';
    protected ?string $maxHeight = '250px';


    protected function getData(): array
    {
        return [
            'datasets' => [[
                'data' => [
                    Student::where('nationality_type', 'Local')->count(),
                    Student::where('nationality_type', 'International')->count(),
                ],
                'backgroundColor' => ['#E9C46A', '#264653'],
            ]],
            'labels' => ['Local', 'International'],
        ];
    }

    protected function getType(): string { return 'doughnut'; }
}
