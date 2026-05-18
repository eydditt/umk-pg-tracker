<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;

class PaymentMethodChart extends ChartWidget
{
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 1;
    protected ?string $heading = 'Payment Method';
    protected ?string $maxHeight = '250px';


   protected function getData(): array
        {
            return [
                'datasets' => [[
                    'data' => [
                        Student::where('payment_method', 'Scholarship')->count(),
                        Student::where('payment_method', 'Self-funded')->count(),
                        Student::where('payment_method', 'Other')->count(),
                        Student::where('payment_method', 'Not-stated')->count(),
                    ],
                    'backgroundColor' => ['#71eeff', '#1E3A8A', '#000000', '#c9c9c9'],
                ]],
                'labels' => ['Scholarship', 'Self-funded', 'Other' , 'Not-stated'],
            ];
        }

    protected function getType(): string { return 'doughnut'; }
}
