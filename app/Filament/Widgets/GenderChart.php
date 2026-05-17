<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;

class GenderChart extends ChartWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;
    protected ?string $heading = 'Gender Distribution';
    protected ?string $maxHeight = '250px';


    protected function getData(): array
    {
        return [
            'datasets' => [[
                'data' => [
                    Student::where('gender', 'Male')->count(),
                    Student::where('gender', 'Female')->count(),
                ],
                'backgroundColor' => ['#2A9D8F', '#E76F51'],
            ]],
            'labels' => ['Male', 'Female'],
        ];
    }

    protected function getType(): string { return 'doughnut'; }
}
