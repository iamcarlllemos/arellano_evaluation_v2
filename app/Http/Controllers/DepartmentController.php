<?php

namespace App\Http\Controllers;

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

        $data = [
            'title' => 'All Departments',
            'action' => $action,
            'active' => '',
        ];

        return view('departments', compact('data'));
    }
}
