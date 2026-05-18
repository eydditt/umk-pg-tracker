<?php

namespace App\Filament\Widgets;

use App\Models\StudentProgress;
use Filament\Widgets\ChartWidget;

class ProgressMilestonesChart extends ChartWidget
{
    protected static ?int $sort = 7;
    protected ?string $heading = 'Progress Milestones Overview (Active-Student)';
    // ❌ Sifu dah BUANG baris $maxHeight kat sini
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $milestones = [
            'Research-Method'       => StudentProgress::whereHas('student', function($q) { 
                                    $q->where('status', 'Active'); 
                                 })->where('research_method', 'Passed')->count(),
                                 
            'Proposal-Defense'       => StudentProgress::whereHas('student', function($q) { 
                                    $q->where('status', 'Active'); 
                                 })->where('pd_status', 'Passed')->count(),
                                 
            'Pre-Viva' => StudentProgress::whereHas('student', function($q) { 
                                    $q->where('status', 'Active'); 
                                 })->where('pre_viva_status', 'Passed')->count(),
                                 
            'Viva'     => StudentProgress::whereHas('student', function($q) { 
                                    $q->where('status', 'Active'); 
                                 })->where('viva_status', 'Passed')->count(), 
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Total Active Students',
                    'data' => array_values($milestones),
                    'backgroundColor' => [
                        'rgba(42, 157, 143, 0.8)',   // Teal (RM)
                        'rgba(124, 58, 237, 0.8)',   // Purple (PD)
                        'rgba(233, 196, 106, 0.8)',  // Yellow (Pre-Viva)
                        'rgba(231, 111, 81, 0.8)'    // Orange (Viva)
                    ],
                ],
            ],
            'labels' => array_keys($milestones),
        ];
    }

    protected function getType(): string
    {
        return 'polarArea';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false, // ✅ SIFU TAMBAH KAT SINI
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'r' => [
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                        'backdropColor' => 'transparent'
                    ],
                ],
            ],
        ];
    }
}