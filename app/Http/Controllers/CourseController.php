<?php

namespace App\Http\Controllers;

use App\Models\CourseModel;
use App\Models\DepartmentModel;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = CourseModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('programs.departments');
            }

        }

        $dirty = DepartmentModel::with('branches')->get();

        $departments = [];
        
        foreach($dirty as $item) {
            $departments[] = (object)[
                'id' => $item->id,
                'name' => $item->name . ' - (' . $item['branches']->name . ')',
            ];
        }
        
        $data = [
            'title' => 'All Departments',
            'active' => '',
            'livewire' => [
                'component' => 'course',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'title' => [
                            'index' => 'All Course',
                            'create' => 'Create Course',
                            'update' => 'Update Course',
                            'delete' => 'Delete Course'
                        ],
                        'subtitle' => [
                            'index' => 'List of all courses created.',
                            'create' => 'Create or add new courses.',
                            'update' => 'Apply changed to selected course.',
                            'delete' => 'Permanently delete selected course'
                        ],
                        'action' => $action,
                        'data' => [
                            'department_id' => [
                                'label' => 'Department Name',
                                'type' => 'select',
                                'placeholder' => 'Type...',
                                'options' => [
                                    'is_from_db' => true,
                                    'data' => $departments,
                                    'no_data' => 'Creat department first.'
                                ]
                            ],
                            'code' => [
                                'label' => 'Course Code',
                                'type' => 'text',
                                'placeholder' => 'Type...',
                            ],
                            'name' => [
                                'label' => 'Course Name',
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
