<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'full_name', 'identity_type', 'identity_no',
        'program_applied', 'prev_edu', 'eng_test',
        'status', 'application_docs_links',
    ];

    protected $casts = [
        'application_docs_links' => 'array',
    ];

    public function student() {
        return $this->hasOne(Student::class);
    }
}