<?php

namespace App\Http\Controllers;

use App\Models\SchoolYearModel;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = SchoolYearModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('programs.departments');
            }
        }

        $data = [
            'title' => 'All Departments',
            'active' => '',
            'livewire' => [
                'component' => 'school-year',   
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'title' => [
                            'index' => 'All School Years',
                            'create' => 'Create School Year',
                            'update' => 'Update School Year',
                            'delete' => 'Delete School Year'
                        ],
                        'subtitle' => [
                            'index' => 'List of all school years created.',
                            'create' => 'Create or add new school year.',
                            'update' => 'Apply changed to selected school year.',
                            'delete' => 'Permanently delete selected school year'
                        ],
                        'action' => $action,
                        'data' => [
                            'name' => [
                                'label' => 'Description',
                                'type' => 'text',
                                'placeholder' => 'Type...',
                            ],
                            'start_year' => [
                                'label' => 'Start Year',
                                'type' => 'select',
                                'placeholder' => 'Type...',
                                'options' => [
                                    'is_from_db' => false,
                                    'data' => [
                                        '2024' => 2024,
                                        '2025' => 2025,
                                        '2026' => 2026,
                                        '2027' => 2027,
                                        '2028' => 2028,
                                        '2029' => 2029,
                                        '2030' => 2030,
                                    ],
                                    'no_data' => 'No school year set'
                                ]
                            ],
                            'semester' => [
                                'label' => 'Semester',
                                'type' => 'select',
                                'placeholder' => 'Type...',
                                'options' => [
                                    'is_from_db' => false,
                                    'data' => [
                                        '1' => '1st Semester',
                                        '2' => '2nd Semester'
                                    ],
                                    'no_data' => 'No semester being set'
                                ]
                            ],
                            'status' => [
                                'label' => 'Status',
                                'type' => 'select',
                                'placeholder' => 'Type...',
                                'options' => [
                                    'is_from_db' => false,
                                    'data' => [
                                        '0' => 'No Yet Opened',
                                        '1' => 'On Going',
                                        '2' => 'Closed',
                                        '3' => 'Finished'
                                    ],
                                    'no_data' => 'No status being set'
                                ]
                            ],
                        ]
                    ],
                ]

            ]
        ];

        return view('template', compact('data'));
    }
}
