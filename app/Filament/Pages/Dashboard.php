<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int | array
    {
        return 4;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\GreetingWidget::class,
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\ProgramTypeChart::class,
            \App\Filament\Widgets\GenderChart::class,
            \App\Filament\Widgets\OriginChart::class,
            \App\Filament\Widgets\PaymentMethodChart::class,
            \App\Filament\Widgets\IntakeTrendChart::class,
            \App\Filament\Widgets\ProgressMilestonesChart::class,
            \App\Filament\Widgets\TopSupervisorChart::class,
            
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download')
                ->label('Download Report')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url('/admin/reports/dashboard')
                ->openUrlInNewTab(),
        ];
    }
}
