<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    protected $fillable = ['staff_no', 'full_name'];

    public function mainStudents() {
        return $this->hasMany(Student::class, 'main_sv_id');
    }
    public function coStudents() {
        return $this->hasMany(Student::class, 'co_sv_id');
    }
}