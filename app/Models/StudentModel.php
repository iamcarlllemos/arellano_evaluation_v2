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
    ];

    public function courses() {
        return $this->belongsTo(CourseModel::class, 'course_id', 'id');
    }

}
