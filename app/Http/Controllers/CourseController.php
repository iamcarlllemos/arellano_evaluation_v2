<?php

namespace App\Http\Controllers;

use App\Models\CourseModel;
use App\Models\DepartmentModel;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $role = auth()->user()->role;
        $assigned_branch = auth()->user()->assigned_branch;

        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = CourseModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('programs.departments');
            }

        }

        $departments_dirty = DepartmentModel::with('branches')
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->where('branch_id', $assigned_branch);
            })
            ->get();

        $departments = [];
        
        if($role === 'admin') {
            foreach($departments_dirty as $department) {
                $departments[] = [
                    'id' => $department->id,
                    'name' => $department->name
                ];
            }
        } else {
            foreach($departments_dirty as $department) {
                $key = $department->branches->id;
                
                if(!isset($departments[$key])) {
                    $departments[$key] = [
                        'id' => $key,
                        'name' => $department->branches->name,
                        'departments' => []
                    ];
                }

                $departments[$key]['departments'][] = [
                    'id' => $department->id,
                    'name' => $department->name
                ];
            }
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
