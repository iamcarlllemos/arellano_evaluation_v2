<?php

namespace App\Http\Controllers;

use App\Models\QuestionnaireModel;
use App\Models\SchoolYearModel;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $slug = $request->input('slug');

            $data = QuestionnaireModel::where('slug', $slug);


            if(!$data->exists()) {
                return redirect()->route('programs.questionnaire');
            }

        }

        $school_year = SchoolYearModel::all();

        $data = [
            'breadcrumbs' => 'Dashboard,programs,questionnaire',
            'livewire' => [
                'component' => 'questionnaire',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'title' => [
                            'index' => 'All Questionnaires',
                            'create' => 'Create Questionnaire',
                            'update' => 'Update Questionnaire',
                            'delete' => 'Delete Questionnaire'
                        ],
                        'subtitle' => [
                            'index' => 'List of all questionnaires created.',
                            'create' => 'Create or add new questionnaire.',
                            'update' => 'Apply changed to selected questionnaire.',
                            'delete' => 'Permanently delete selected questionnaire'
                        ],
                        'action' => $action,
                        'index' => [
                            'title' => 'All Questionnaires',
                            'subtitle' => 'List of all questionnaires created.'
                        ],
                        'create' => [
                            'title' => 'Create Questionnaire',
                            'subtitle' => 'Create or add new criteria.',
                            'data' => [
                                'school_year_id' => [
                                    'label' => 'School Year',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $school_year,
                                        'no_data' => 'Create school year first'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12'
                                ],
                                'name' => [
                                    'label' => 'Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12'
                                ]
                            ]
                        ],
                        'update' => [
                            'title' => 'Update Questionnaire',
                            'subtitle' => 'Create or add new criteria.',
                            'data' => [
                                'school_year_id' => [
                                    'label' => 'School Year',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $school_year,
                                        'no_data' => 'Create school year first'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12'
                                ],
                                'name' => [
                                    'label' => 'Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12'
                                ]
                            ]
                        ],
                        'delete' => [
                            'title' => 'Delete Questionnaire',
                            'subtitle' => 'Create or add new criteria.',
                            'data' => [
                                'school_year_id' => [
                                    'label' => 'School Year',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $school_year,
                                        'no_data' => 'Create school year first'
                                    ],
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12'
                                ],
                                'name' => [
                                    'label' => 'Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12'
                                ]
                            ]
                        ],
                    ],
                ]
            ]
        ];

        return view('template', compact('data'));
    }
}
