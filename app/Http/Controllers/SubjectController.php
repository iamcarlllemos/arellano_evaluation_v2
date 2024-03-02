<?php

namespace App\Http\Controllers;

use App\Models\SubjectModel;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = SubjectModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('programs.departments');
            }

        }

        $data = [
            'title' => 'All Departments',
            'action' => $action,
            'active' => '',
        ];

        return view('subjects', compact('data'));
    }
}
