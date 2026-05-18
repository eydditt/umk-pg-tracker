<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;

class ProgramTypeChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 1;
    protected ?string $heading = 'Program Type';
    protected ?string $maxHeight = '250px';


    protected function getData(): array
    {
        return [
            'datasets' => [[
                'data' => [
                    Student::where('program_type', 'PhD')->count(),
                    Student::where('program_type', 'Master')->count(),
                ],
                'backgroundColor' => ['#1E3A8A', '#71eeff'],
            ]],
            'labels' => ['PhD', 'Master'],
        ];
    }

    protected function getType(): string { return 'doughnut'; }
}
