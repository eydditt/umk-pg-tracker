<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    protected $table = 'student_progress';

    protected $fillable = [
        'student_id', 'eng_test_status', 'research_method',
        'pd_status', 'pre_viva_status', 'viva_status', 'gdrive_links',
    ];

    protected $casts = [
        'gdrive_links' => 'array',
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }
}