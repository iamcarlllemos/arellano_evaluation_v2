<?php

namespace App\Http\Controllers;

use App\Models\BranchModel;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {
            $id = $request->input('id');
            $data = BranchModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('programs.branches');
            }

        }

        $data = [
            'title' => 'All Branches',
            'active' => '',
            'livewire' => [
                'component' => 'branch',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'title' => [
                            'index' => 'All Branches',
                            'create' => 'Create Branch',
                            'update' => 'Update Branch',
                            'delete' => 'Delete Branch'
                        ],
                        'subtitle' => [
                            'index' => 'List of all branches created.',
                            'create' => 'Create or add new branches.',
                            'update' => 'Apply changed to selected branch.',
                            'delete' => 'Permanently delete selected branch'
                        ],
                        'action' => $action,
                        'data' => [
                            'name' => [
                                'label' => 'Branch Name',
                                'type' => 'text',
                                'placeholder' => 'Type...',
                            ],
                            'image' => [
                                'label' => 'Image',
                                'type' => 'file',
                                'placeholder' => 'Upload Image',
                            ],
                        ]
                    ],
                ]
            ],
        ];

        return view('template', compact('data'));
    }
}
