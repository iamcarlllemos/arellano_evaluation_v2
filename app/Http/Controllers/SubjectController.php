<?php

namespace App\Http\Controllers;

use App\Models\SubjectModel;
use App\Models\CourseModel;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = SubjectModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('programs.departments');
            }

        }

        $courses = CourseModel::with(['departments.branches'])->get();

        $data = [
            'title' => 'All Departments',
            'active' => '',
            'livewire' => [
                'component' => 'subject',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'title' => [
                            'index' => 'All Subjects',
                            'create' => 'Create Subject',
                            'update' => 'Update Subject',
                            'delete' => 'Delete Subject'
                        ],
                        'subtitle' => [
                            'index' => 'List of all subjects created.',
                            'create' => 'Create or add new subject.',
                            'update' => 'Apply changed to selected subject.',
                            'delete' => 'Permanently delete selected subject'
                        ],
                        'action' => $action,
                        'data' => [
                            'course_id' => [
                                'label' => 'Course Name',
                                'type' => 'select',
                                'placeholder' => 'Type...',
                                'options' => [
                                    'is_from_db' => true,
                                    'data' => $courses,
                                    'no_data' => 'Create course first.'
                                ]
                            ],
                            'code' => [
                                'label' => 'Subject Code',
                                'type' => 'text',
                                'placeholder' => 'Type...',
                            ],
                            'name' => [
                                'label' => 'Subject Name',
                                'type' => 'text',
                                'placeholder' => 'Type...',
                            ],
                        ]
                    ],
                ]
            ]
        ];

        return view('template', compact('data'));
    }
}
