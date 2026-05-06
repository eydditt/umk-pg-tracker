<?php

namespace App\Filament\Resources\Applicants\Widgets;

use App\Models\Applicant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApplicantStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total    = Applicant::count();
        $pending  = Applicant::where('status', 'Pending')->count();
        $approved = Applicant::where('status', 'Approved')->count();
        $rejected = Applicant::where('status', 'Rejected')->count();

        return [
            Stat::make('Total Applicants', $total)
                ->description('All submitted applications')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('info'),

            Stat::make('Pending', $pending)
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Approved', $approved)
                ->description('Converted to students')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Rejected', $rejected)
                ->description('Applications rejected')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}