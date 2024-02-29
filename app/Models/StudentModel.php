<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentModel extends Model
{
    use HasFactory;

    protected $table = 'afears_student';
    protected $fillable = [
        'course_id',
        'student_number',
        'firstname',
        'lastname',
        'middlename',
        'gender',
        'birthday',
        'year_level',
        'image',
        'email',
        'username',
        'password',
        'date_added',
        'date_updated'
    ];

}
