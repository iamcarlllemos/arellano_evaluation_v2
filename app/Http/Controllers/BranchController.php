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
            'breadcrumbs' => 'Dashboard,programs,branches',
            'livewire' => [
                'component' => 'branch',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'action' => $action,
                        'index' => [
                            'title' => 'All Branches',
                            'subtitle' => 'List of all branches created.'
                        ],
                        'create' => [
                            'title' => 'Create Branch',
                            'subtitle' => 'Create or add new branches.',
                            'data' => [
                                'name' => [
                                    'label' => 'Branch Name',
                                    'type' => 'text',
                                    'placeholder' => 'Type...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12'
                                ],
                                'image' => [
                                    'label' => 'Image',
                                    'type' => 'file',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12'
                                ],
                            ]
                        ],
                        'update' => [
                            'title' => 'Update Branch',
                            'subtitle' => 'Apply changed to selected branch.',
                            'data' => [
                                'name' => [
                                    'label' => 'Branch Name',
                                    'type' => 'text',
                                    'placeholder' => 'Type...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12'
                                ],
                                'image' => [
                                    'label' => 'Image',
                                    'type' => 'file',
                                    'placeholder' => 'Upload Image',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12'
                                ],
                            ]
                        ],
                        'delete' => [
                            'title' => 'Delete Branch',
                            'subtitle' => 'Permanently delete selected branch.',
                            'data' => [
                                'name' => [
                                    'label' => 'Branch Name',
                                    'type' => 'text',
                                    'placeholder' => 'Type...',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12'
                                ],
                            ]
                        ]
                    ],
                ]
            ],
        ];

        return view('template', compact('data'));
    }
}
