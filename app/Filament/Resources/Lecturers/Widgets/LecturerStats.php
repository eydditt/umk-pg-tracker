<?php

namespace App\Filament\Resources\Lecturers\Widgets;

use App\Models\Lecturer;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LecturerStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalLecturers = Lecturer::count();
        
        
        $totalSupervising = Lecturer::whereHas('mainStudents', function ($query) {
            $query->where('status', 'Active');
        })->count();
        
     
        $totalStudentsSupervised = Student::whereNotNull('main_sv_id')
            ->where('status', 'Active')
            ->count();
            
      
        $unsupervised = Student::whereNull('main_sv_id')
            ->where('status', 'Active')
            ->count();

        return [
            Stat::make('Total Lecturers', $totalLecturers)
                ->description('Registered in system')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('info'),

            Stat::make('Active Supervisors', $totalSupervising)
                ->description('Lecturers supervising active students')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),

            Stat::make('Students Under Supervision', $totalStudentsSupervised)
                ->description('Active students with Main SV')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Unsupervised Students', $unsupervised)
                ->description('Active students without SV')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger'),
        ];
    }
}