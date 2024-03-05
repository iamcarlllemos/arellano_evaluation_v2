<?php

namespace App\Http\Controllers;

use App\Models\CourseModel;
use App\Models\StudentModel;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = StudentModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('accounts.student');
            }

        }

        $dirty = CourseModel::with('departments.branches')->get();

        $courses = [];
        
        foreach($dirty as $item) {
            $courses[] = (object)[
                'id' => $item->id,
                'name' => $item->name . ' - (' . $item['departments']['branches']->name . ')',
            ];
        }

        $data = [
            'title' => 'All Students',
            'active' => '',
            'livewire' => [
                'component' => 'student',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'title' => [
                            'index' => 'All Students',
                            'create' => 'Create Student',
                            'update' => 'Update Student',
                            'delete' => 'Delete Student'
                        ],
                        'subtitle' => [
                            'index' => 'List of all students created.',
                            'create' => 'Create or add new students.',
                            'update' => 'Apply changed to selected student.',
                            'delete' => 'Permanently delete selected student'
                        ],
                        'action' => $action,
                        'data' => [
                            'student_number' => [
                                'label' => 'Student Number',
                                'type' => 'text',
                                'placeholder' => 'ex. 20-00780',
                                'is_required' => true,
                                'col-span' => '6',
                            ],
                            'course_id' => [
                                'label' => 'Course',
                                'type' => 'select',
                                'options' => [
                                    'is_from_db' => true,
                                    'data' => $courses,
                                    'no_data' => 'Create course first'
                                ],
                                'is_required' => true,
                                'col-span' => '3',
                            ],
                            'year_level' => [
                                'label' => 'Year Level',
                                'type' => 'select',
                                'options' => [
                                    'is_from_db' => false,
                                    'data' => [
                                        '1' => '(1st) First Year',
                                        '2' => '(2nd) Second Year',
                                        '3' => '(3rd) Third Year',
                                        '4' => '(4th) Fourth Year'
                                    ],
                                    'no_data' => 'No data found'
                                ],
                                'is_required' => true,
                                'col-span' => '3',
                            ],
                            'firstname' => [
                                'label' => 'First Name',
                                'type' => 'text',
                                'placeholder' => 'ex. John Paul',
                                'is_required' => true,
                                'col-span' => '4',
                            ],
                            'middlename' => [
                                'label' => 'Middle Name',
                                'type' => 'text',
                                'placeholder' => 'ex. Mariano',
                                'is_required' => false,
                                'col-span' => '4',
                            ],
                            'lastname' => [
                                'label' => 'Last Name',
                                'type' => 'text',
                                'placeholder' => 'ex. Llemos',
                                'is_required' => true,
                                'col-span' => '4',
                            ],
                            'gender' => [
                                'label' => 'Gender',
                                'type' => 'select',
                                'options' => [
                                    'is_from_db' => false,
                                    'data' => [
                                        '1' => 'Male',
                                        '2' => 'Female',
                                        '3' => 'Prefer not to say'
                                    ],
                                    'no_data' => 'No data'
                                ],
                                'is_required' => true,
                                'col-span' => '6',
                            ],
                            'birthday' => [
                                'label' => 'Birthday',
                                'type' => 'date',
                                'placeholder' => 'Type ...',
                                'is_required' => true,
                                'col-span' => '6',
                            ],
                            'image' => [
                                'label' => 'Profile Image',
                                'type' => 'file',
                                'is_required' => true,
                                'col-span' => '12',
                            ],
                            'email' => [
                                'label' => 'Email',
                                'type' => 'email',
                                'placeholder' => 'Type ...',
                                'is_required' => true,
                                'col-span' => '6',
                            ],
                            'username' => [
                                'label' => 'Username',
                                'type' => 'text',
                                'placeholder' => 'Type...',
                                'is_required' => true,
                                'col-span' => '6',
                            ],
                            'password' => [
                                'label' => 'Password',
                                'type' => 'password',
                                'placeholder' => '••••••••',
                                'is_required' => true,
                                'col-span' => '12',
                            ],
                            'password_repeat' => [
                                'label' => 'Password Repeat',
                                'type' => 'password',
                                'placeholder' => '••••••••',
                                'is_required' => true,
                                'col-span' => '12',
                            ],
                        ]
                    ],
                ]
            ]
        ];

        return view('template', compact('data'));
    }
}
