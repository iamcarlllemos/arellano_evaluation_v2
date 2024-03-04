<?php

namespace App\Http\Controllers;

use App\Models\CriteriaModel;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = CriteriaModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('programs.criteria');
            }

        }

        $data = [
            'title' => 'All Criterias',
            'active' => '',
            'livewire' => [
                'component' => 'criteria',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'title' => [
                            'index' => 'All Criterias',
                            'create' => 'Create Criteria',
                            'update' => 'Update Criteria',
                            'delete' => 'Delete Criteria'
                        ],
                        'subtitle' => [
                            'index' => 'List of all criterias created.',
                            'create' => 'Create or add new criteria.',
                            'update' => 'Apply changed to selected criteria.',
                            'delete' => 'Permanently delete selected criteria'
                        ],
                        'action' => $action,
                        'data' => [
                            'name' => [
                                'label' => 'Name',
                                'type' => 'text',
                                'placeholder' => 'Type...',
                            ]
                        ]
                    ],
                ]
            ]
        ];

        return view('template', compact('data'));
    }
}
