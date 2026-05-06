<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    protected $table = 'student_progress';

    protected $fillable = [
        'student_id', 'eng_test_status', 'research_method',
        'pd_status', 'pre_viva_status', 'viva_status',
        'scholarship_status', 'tuition_fee_status',
        'progress_report_status', 'last_progress_report_date',
        'degree_verification_status', 'graduation_date',
        'gdrive_links',
    ];

    protected $casts = [
        'gdrive_links' => 'array',
        'last_progress_report_date' => 'date',
        'graduation_date' => 'date',
    ];
    public function student() {
        return $this->belongsTo(Student::class);
    }
}