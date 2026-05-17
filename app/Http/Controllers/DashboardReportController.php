<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\StudentProgress;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class DashboardReportController extends Controller
{
    public function generate()
    {
        $total_students       = Student::count();
        $active_students      = Student::where('status', 'Active')->count();
        $completed_students   = Student::where('status', 'Completed')->count();
        $deferred_students    = Student::where('status', 'Deferred')->count();
        $terminated_students  = Student::where('status', 'Terminated')->count();
        $total_lecturers      = Lecturer::count();
        $total_applicants     = Applicant::count();
        $pending_applicants   = Applicant::where('status', 'Pending')->count();
        $unsupervised         = Student::whereNull('main_sv_id')->count();
        $pending_english      = StudentProgress::where('eng_test_status', 'Pending')->count();

        $phd_students         = Student::where('program_type', 'PhD')->count();
        $master_students      = Student::where('program_type', 'Master')->count();
        $male_students        = Student::where('gender', 'Male')->count();
        $female_students      = Student::where('gender', 'Female')->count();

        $local_students         = Student::whereHas('applicant', fn($q) => $q->where('identity_type', 'IC'))->count();
        $international_students = Student::whereHas('applicant', fn($q) => $q->where('identity_type', 'Passport'))->count();

        $payment_scholarship  = Student::where('payment_method', 'Scholarship')->count();
        $payment_self         = Student::where('payment_method', 'Self-funded')->count();
        $payment_other        = Student::where('payment_method', 'Other')->count();
        $payment_not_stated   = Student::where('payment_method', 'Not-stated')->orWhereNull('payment_method')->count();

        $passed_english       = StudentProgress::where('eng_test_status', 'Passed')->count();
        $pre_viva_completed   = StudentProgress::where('pre_viva_status', 'Passed')->count();
        $viva_completed       = StudentProgress::where('viva_status', 'Passed')->count();
        $degree_verified      = StudentProgress::whereIn('degree_verification_status', ['Verified', 'Awarded'])->count();

        $top_supervisors = Lecturer::withCount('mainStudents')
            ->orderByDesc('main_students_count')
            ->limit(8)
            ->get();

        $intake_data = Student::select('intake_session', DB::raw('count(*) as total'))
            ->whereNotNull('intake_session')
            ->groupBy('intake_session')
            ->orderBy('intake_session')
            ->get();

        $generated_at = now()->format('d M Y, h:i A');

        $data = compact(
            'total_students', 'active_students', 'completed_students',
            'deferred_students', 'terminated_students', 'total_lecturers',
            'total_applicants', 'pending_applicants', 'unsupervised',
            'pending_english', 'phd_students', 'master_students',
            'male_students', 'female_students', 'local_students',
            'international_students', 'payment_scholarship', 'payment_self',
            'payment_other', 'payment_not_stated', 'passed_english',
            'pre_viva_completed', 'viva_completed', 'degree_verified',
            'top_supervisors', 'intake_data', 'generated_at'
        );

        $pdf = Pdf::loadView('reports.dashboard-report', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'          => 'dejavu sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'isCssFloatEnabled'    => true,
                'isPhpEnabled'         => true,
                'dpi'                  => 150,
                'enable_css_float'     => true,
            ]);

        $filename = 'UMK-PG-Dashboard-Report-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}