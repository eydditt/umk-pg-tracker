<?php

namespace App\Filament\Widgets;

use App\Models\Applicant;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\StudentProgress;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    
    protected function getStats(): array
    {
        $totalStudents    = Student::count();
        
        $activeStudents   = Student::where('status', 'Active')->count(); 
        $graduated        = Student::where('status', 'Completed')->count();
        
        
        $unsupervised     = Student::whereNull('main_sv_id')
                                   ->where('status', 'Active')
                                   ->count();
                                   
        $pendingEnglish   = StudentProgress::where('eng_test_status', 'Pending')->count();
        
       
        $unsupervisedPct  = $activeStudents > 0 ? round(($unsupervised / $activeStudents) * 100) : 0;
        $pendingEngPct    = $activeStudents > 0 ? round(($pendingEnglish / $activeStudents) * 100) : 0;

        return [
            Stat::make('Applicants', Applicant::where('status', 'Pending')->count())
                ->description('Pending approval')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning'),

            Stat::make('Students', $totalStudents)
                
                ->description('Active: ' . $activeStudents) 
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),

            Stat::make('Lecturers', Lecturer::count())
                ->description('Total registered')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('info'),

            Stat::make('Graduated', $graduated)
                ->description('Completed students')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('primary'),

            Stat::make('Unsupervised', $unsupervisedPct . '%')
                
                ->description($unsupervised . ' active students without SV') 
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger'),

            Stat::make('Pending English', $pendingEngPct . '%')
                ->description($pendingEnglish . ' students pending')
                ->descriptionIcon('heroicon-o-language')
                ->color('warning'),
        ];
    }
}