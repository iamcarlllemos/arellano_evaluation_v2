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
            'action' => $action,
            'active' => '',
        ];

        return view('branches', compact('data'));
    }
}
