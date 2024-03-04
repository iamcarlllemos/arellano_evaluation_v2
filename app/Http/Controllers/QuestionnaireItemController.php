<?php

namespace App\Http\Controllers;

use App\Models\CriteriaModel;
use App\Models\QuestionnaireModel;
use App\Models\SchoolYearModel;
use Illuminate\Http\Request;

class QuestionnaireItemController extends Controller
{
    public function index($slug) {

        $slug = QuestionnaireModel::where('slug', $slug);
        
        if(!$slug->exists()) {
            return redirect()->route('programs.questionnaire');
        }

        $id = $slug->first()->id;

        $criteria = CriteriaModel::all();

        $data = [
            'title' => 'All Questionnaires',
            'active' => '',
            'livewire' => [
                'component' => 'questionnaire-item',
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
                        'data' => [
                            'questionnaire_id' => [
                                'label' => 'Questionnaire ',
                                'type' => 'hidden',
                                'placeholder' => '',
                                'value' => $id
                            ],
                            'questionnaire_item_id' => [
                                'label' => '',
                                'type' => 'hidden',
                                'placeholder' => '',
                                'value' => $id
                            ],
                            'criteria_id' => [
                                'label' => 'Criteria',
                                'type' => 'select',
                                'placeholder' => 'Type...',
                                'options' => [
                                    'is_from_db' => true,
                                    'data' => $criteria,
                                    'no_data' => 'Create criteria first'
                                ]
                            ],
                            'item' => [
                                'label' => 'Questionnaire Item',
                                'type' => 'textarea',
                                'placeholder' => 'Write Something...',
                            ]
                        ]
                    ],
                ]
            ]
        ];

        return view('template', compact('data'));
    }
}
