<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
    'applicant_id', 'matric_no', 'email', 'application_docs_links',
    'program_type', 'intake_session', 'gender', 'nationality_type',
    'payment_method', 'main_sv_id', 'co_sv_id', 'status',
];

    protected $casts = [
        'application_docs_links' => 'array',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function progress()
    {
        return $this->hasOne(StudentProgress::class);
    }

    public function mainSupervisor()
    {
        return $this->belongsTo(Lecturer::class, 'main_sv_id');
    }

    public function coSupervisor()
    {
        return $this->belongsTo(Lecturer::class, 'co_sv_id');
    }
}