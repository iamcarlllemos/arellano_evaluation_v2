<?php

namespace App\Http\Controllers;

use App\Models\BranchModel;
use App\Models\DepartmentModel;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = DepartmentModel::where('id', $id);
            
            if(!$data->exists()) {
                return redirect()->route('programs.departments');
            }
        } 


        $branches = BranchModel::with('departments')->get();

        $data = [
            'title' => 'All Departments',
            'active' => '',
            'branches' => $branches,
            'livewire' => [
                'component' => 'department',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'title' => [
                            'index' => 'All Departments',
                            'create' => 'Create Department',
                            'update' => 'Update Department',
                            'delete' => 'Delete Department'
                        ],
                        'subtitle' => [
                            'index' => 'Lists of all departments created.',
                            'create' => 'Create or add new departments.',
                            'update' => 'Apply changed to selected branch.',
                            'delete' => 'Permanently delete selected banch'
                        ],
                        'action' => $action,
                        'data' => [
                            'branch_id' => [
                                'label' => 'Branch Name',
                                'type' => 'select',
                                'placeholder' => '',
                                'options' => [
                                    'is_from_db' => true,
                                    'data' => $branches,
                                    'no_data' => 'Create branch first.'
                                ]
                            ],  
                            'name' => [
                                'label' => 'Department Name',
                                'type' => 'text',
                                'placeholder' => 'Type...',
                            ],
                        ],
                    ],
                ]
            ]
            
        ];

        return view('template', compact('data'));
    }
}
