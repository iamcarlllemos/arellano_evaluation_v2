<?php

namespace App\Http\Controllers;

use App\Models\BranchModel;
use App\Models\CurriculumTemplateModel;
use App\Models\DepartmentModel;
use App\Livewire\CurriculumTemplate;
use Illuminate\Http\Request;

class CurriculumTemplateController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = CurriculumTemplateModel::where('id', $id);
            
            if(!$data->exists()) {
                return redirect()->route('linking.curriculum-template');
            }
        } 

        $data = [
            'title' => 'All Curriculum Templates',
            'active' => '',
            'livewire' => [
                'component' => 'curriculum-template',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'title' => [
                            'index' => 'All Curriculum Templates',
                            'create' => 'Create Curriculum Template',
                            'update' => 'Update Curriculum Template',
                            'delete' => 'Delete Curriculum Template'
                        ],
                        'subtitle' => [
                            'index' => 'Lists of all curriculum templates created.',
                            'create' => 'Create or add new curriculum template.',
                            'update' => 'Apply changed to selected curriculum template.',
                            'delete' => 'Permanently delete selected curriculum template.',
                        ],
                        'action' => $action,
                        'data' => [
                            'year_level' => [
                                'label' => 'Year Level',
                                'type' => 'select',
                                'on_change' => 'loadYear',
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
                                'col-span' => '6',
                            ],
                            'subject_sem' => [
                                'label' => 'Semester',
                                'type' => 'select',
                                'on_change' => '',
                                'options' => [
                                    'is_from_db' => false,
                                    'data' => [
                                        '1' => '(1st) First Semester',
                                        '2' => '(2nd) Second Semester',
                                    ],
                                    'no_data' => 'No data found'
                                ],
                                'is_required' => true,
                                'col-span' => '6',
                            ]
                        ],
                    ],
                ]
            ]
            
        ];

        return view('template', compact('data'));
    }
}
