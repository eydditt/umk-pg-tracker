<?php

namespace App\Filament\Widgets;

use App\Helpers\CountryList;
use App\Models\Student;
use Filament\Widgets\ChartWidget;

class OriginChart extends ChartWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 2;   
    protected ?string $heading = 'Student Origin by Region';
    protected ?string $maxHeight = '340px';            

    protected function getData(): array
    {
        $students = Student::whereNotNull('country')->get();

        $regions = [];
        foreach ($students as $student) {
            $region = CountryList::region($student->country);
            $regions[$region] = ($regions[$region] ?? 0) + 1;
        }

        $noCountry = Student::whereNull('country')->count();
        if ($noCountry > 0) {
            $local = Student::whereNull('country')
                ->whereHas('applicant', fn($q) => $q->where('identity_type', 'IC'))
                ->count();
            $intl = Student::whereNull('country')
                ->whereHas('applicant', fn($q) => $q->where('identity_type', 'Passport'))
                ->count();
            if ($local > 0) $regions['Southeast Asia'] = ($regions['Southeast Asia'] ?? 0) + $local;
            if ($intl > 0) $regions['Other'] = ($regions['Other'] ?? 0) + $intl;
        }

        arsort($regions);

        $colors = [
            'Southeast Asia' => '#0F766E', 
            'East Asia'      => '#0369A1', 
            'South Asia'     => '#3B82F6',
            'Middle East'    => '#8B5CF6', 
            'Africa'         => '#D946EF', 
            'Europe'         => '#F43F5E', 
            'Americas'       => '#F97316', 
            'Oceania'        => '#EAB308', 
            'Central Asia' => '#06B6D4',
            'Other'          => '#9CA3AF', 
        ];

        return [
            'datasets' => [[
                'data'            => array_values($regions),
                'backgroundColor' => array_map(fn($r) => $colors[$r] ?? '#9CA3AF', array_keys($regions)),
                'borderWidth'     => 0, // Buang garis putih untuk nampak lebih bersih
                'hoverOffset'     => 4, // Kesan timbul sikit bila mouse hover
            ]],
            'labels' => array_keys($regions),
        ];
    }

    protected function getType(): string { return 'doughnut'; }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'cutout' => '55%',
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 14,
                        'padding' => 16,
                        'font' => ['size' => 13],
                    ],
                ],
            ],
        ];
    }
}