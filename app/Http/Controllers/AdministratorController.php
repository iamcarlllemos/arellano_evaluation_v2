<?php

namespace App\Http\Controllers;

use App\Models\BranchModel;
use App\Models\User;
use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = User::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('accounts.student');
            }

        }

        $branches = BranchModel::all();

        $data = [
            'title' => 'All Administrators',
            'active' => '',
            'livewire' => [
                'component' => 'administrator',
                'data' => [
                    'lazy' => false,
                    'form' => [                    
                        'action' => $action,
                        'index' => [
                            'title' => 'All Administrators',
                            'subtitle' => 'List of all administrators created.'
                        ],
                        'create' => [
                            'title' => 'Create Administrator',
                            'subtitle' => 'Create or add new administrator based on their roles.',
                            'data' => [
                                'firstname' => [
                                    'label' => 'First Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. John Paul',
                                    'is_required' => true,
                                    'col-span' => '6',
                                ],
                                'lastname' => [
                                    'label' => 'Last Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. Llemos',
                                    'is_required' => true,
                                    'col-span' => '6',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'is_required' => true,
                                    'col-span' => '6',
                                ],
                                'role' => [
                                    'label' => 'Role',
                                    'type' => 'select',
                                    'is_required' => true,
                                    'col-span' => '6',
                                    'options' => [
                                        'is_from_db' => false,
                                        'data' => [
                                            'superadmin' => 'Super Administrator',
                                            'admin' => 'Administrator',
                                        ],
                                        'no_data' => 'No data found'
                                    ]
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'is_required' => true,
                                    'col-span' => '6',
                                ],
                                'username' => [
                                    'label' => 'Username',
                                    'type' => 'text',
                                    'placeholder' => 'Type...',
                                    'is_required' => true,
                                    'col-span' => '12',
                                ],
                                'password' => [
                                    'label' => 'Password',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'is_required' => true,
                                    'col-span' => '12',
                                ],
                                'password_repeat' => [
                                    'label' => 'Password Repeat',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'is_required' => true,
                                    'col-span' => '12',
                                ],
                            ]
                        ],
                        'update' => [
                            'title' => 'Update Administrator',
                            'subtitle' => 'Apply changes to selected administrator.',
                            'data' => [
                                'firstname' => [
                                    'label' => 'First Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. John Paul',
                                    'is_required' => true,
                                    'col-span' => '6',
                                ],
                                'lastname' => [
                                    'label' => 'Last Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. Llemos',
                                    'is_required' => true,
                                    'col-span' => '6',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'is_required' => true,
                                    'col-span' => '6',
                                ],
                                'role' => [
                                    'label' => 'Role',
                                    'type' => 'select',
                                    'is_required' => true,
                                    'col-span' => '6',
                                    'options' => [
                                        'is_from_db' => false,
                                        'data' => [
                                            'superadmin' => 'Super Administrator',
                                            'admin' => 'Administrator',
                                        ],
                                        'no_data' => 'No data found'
                                    ]
                                ],
                                'branch' => [
                                    'label' => 'Branch',
                                    'type' => 'select',
                                    'is_required' => true,
                                    'col-span' => '6',
                                    'options' => [
                                        'is_from_db' => true,
                                        'data' => $branches,
                                        'no_data' => 'No data found'
                                    ],
                                    'col-span' => '12',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'is_required' => true,
                                    'col-span' => '6',
                                ],
                                'username' => [
                                    'label' => 'Username',
                                    'type' => 'text',
                                    'placeholder' => 'Type...',
                                    'is_required' => true,
                                    'col-span' => '12',
                                ],
                                'password' => [
                                    'label' => 'Password',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'is_required' => true,
                                    'col-span' => '12',
                                ],
                                'password_repeat' => [
                                    'label' => 'Password Repeat',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'is_required' => true,
                                    'col-span' => '12',
                                ],
                            ]
                        ],
                        'delete' => [
                            'title' => 'Delete Administrator',
                            'subtitle' => 'Permanently delete to selected administrator.',
                            'data' => [
                                'firstname' => [
                                    'label' => 'First Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. John Paul',
                                    'is_required' => true,
                                    'col-span' => '6',
                                ],
                                'lastname' => [
                                    'label' => 'Last Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. Llemos',
                                    'is_required' => true,
                                    'col-span' => '6',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'is_required' => true,
                                    'col-span' => '6',
                                ],
                                'role' => [
                                    'label' => 'Role',
                                    'type' => 'select',
                                    'is_required' => true,
                                    'col-span' => '6',
                                    'options' => [
                                        'is_from_db' => false,
                                        'data' => [
                                            'superadmin' => 'Super Administrator',
                                            'admin' => 'Administrator',
                                        ],
                                        'no_data' => 'No data found'
                                    ]
                                ],
                                'branch' => [
                                    'label' => 'Branch',
                                    'type' => 'select',
                                    'is_required' => true,
                                    'col-span' => '6',
                                    'options' => [
                                        'is_from_db' => true,
                                        'data' => $branches,
                                        'no_data' => 'No data found'
                                    ],
                                    'col-span' => '12',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'is_required' => true,
                                    'col-span' => '6',
                                ],
                                'username' => [
                                    'label' => 'Username',
                                    'type' => 'text',
                                    'placeholder' => 'Type...',
                                    'is_required' => true,
                                    'col-span' => '12',
                                ],
                            ]
                        ]
                    ],
                ]
            ]
        ];

        return view('template', compact('data'));
    }
}
