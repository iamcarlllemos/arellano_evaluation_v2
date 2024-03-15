<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SubjectModel;
use App\Models\CourseModel;

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
                    $courses[$key] = (object)[
                        'id' => $key,
                        'name' => $course->departments->branches->name,
                        'courses' => []
                    ];
                }

                $courses[$key]->courses[] = (object) [
                    'id' => $course->id,
                    'name' => $course->name
                ];
            }
        }

        $data = [
            'breadcrumbs' => 'Dashboard,programs,subjects',
            'livewire' => [
                'component' => 'admin.subject',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'action' => $action,
                        'index' => [
                            'title' => 'All Subjects',
                            'subtitle' => 'List of all subjects created.'
                        ],
                        'create' => [
                            'title' => 'Create Subjects',
                            'subtitle' => 'Create or add new subjects.',
                            'data' => [
                                'course_id' => [
                                    'label' => 'Course Name',
                                    'type' => 'select',
                                    'placeholder' => 'Write something...',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => 'courses',
                                        'data' => $courses,
                                        'no_data' => 'Create course first.'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'code' => [
                                    'label' => 'Subject Code',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'name' => [
                                    'label' => 'Subject Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                            ]
                        ],
                        'update' => [
                            'title' => 'Update Subjects',
                            'subtitle' => 'Apply changes to selected subject.',
                            'data' => [
                                'course_id' => [
                                    'label' => 'Course Name',
                                    'type' => 'select',
                                    'placeholder' => 'Write something...',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => 'courses',
                                        'data' => $courses,
                                        'no_data' => 'Create course first.'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'code' => [
                                    'label' => 'Subject Code',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'name' => [
                                    'label' => 'Subject Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                            ]
                        ],
                        'delete' => [
                            'title' => 'Delete Subject',
                            'subtitle' => 'Permanently delete selected subject.',
                            'data' => [
                                'course_id' => [
                                    'label' => 'Course Name',
                                    'type' => 'select',
                                    'placeholder' => 'Write something...',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => 'courses',
                                        'data' => $courses,
                                        'no_data' => 'Create course first.'
                                    ],
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12',
                                ],
                                'code' => [
                                    'label' => 'Subject Code',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12',
                                ],
                                'name' => [
                                    'label' => 'Subject Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12',
                                ],
                            ]
                        ],
                    ],
                ]
            ]
        ];

        return view('template', compact('data'));
    }
}
