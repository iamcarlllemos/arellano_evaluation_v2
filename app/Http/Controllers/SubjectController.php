<?php

namespace App\Http\Controllers;

use App\Models\SubjectModel;
use App\Models\CourseModel;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';

        $role = auth()->user()->role;
        $assigned_branch = auth()->user()->assigned_branch;

        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = SubjectModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('programs.departments');
            }

        }

        $dirty = CourseModel::with(['departments.branches'])->get();

        $courses_dirty = CourseModel::with('departments.branches')
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->whereHas('departments.branches', function($subQuery) use($assigned_branch) {
                    $subQuery->where('branch_id', $assigned_branch);
                });
            })
            ->get();

        $courses = [];
        
        if($role === 'admin') {
            foreach($courses_dirty as $course) {
                $courses[] = [
                    'id' => $course->id,
                    'name' => $course->name
                ];
            }
        } else {
            foreach($courses_dirty as $course) {
                $key = $course->departments->branches->id;
                
                if(!isset($courses[$key])) {
                    $courses[$key] = [
                        'id' => $key,
                        'name' => $course->departments->branches->name,
                        'courses' => []
                    ];
                }

                $courses[$key]['courses'][] = [
                    'id' => $course->id,
                    'name' => $course->name
                ];
            }
        }

        $data = [
            'breadcrumbs' => 'Dashboard,programs,subjects',
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
