<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Student extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'matric_no',
        'name',
        'program',
        'faculty',
        'status',
        'email' , 
    ];
}