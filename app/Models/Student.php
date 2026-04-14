<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'applicant_id', 'matric_no', 'program_type',
        'gender', 'payment_method', 'main_sv_id',
        'co_sv_id', 'status',
    ];

    public function applicant() {
        return $this->belongsTo(Applicant::class);
    }
    public function progress() {
        return $this->hasOne(StudentProgress::class);
    }
    public function mainSupervisor() {
        return $this->belongsTo(Lecturer::class, 'main_sv_id');
    }
    public function coSupervisor() {
        return $this->belongsTo(Lecturer::class, 'co_sv_id');
    }
}