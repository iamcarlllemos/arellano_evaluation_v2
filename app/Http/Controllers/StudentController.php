<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index() {
        $data = [
            'title' => 'All Students',
            'active' => 'maintenance',
            'course' => [],
            'department' => [],
            'student' => []
        ];
        return view('student', compact('data'));
    }


}
