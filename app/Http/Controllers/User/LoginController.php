<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentModel;
use Auth;

class LoginController extends Controller
{

    public function index() {
        return view('user.login');
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        $input = $request->only('username', 'password');

        if(Auth::guard('users')->attempt($input)) {
            $users = StudentModel::where('username', $request->input('username'))->first();
            Auth::guard('users')->login($users);
            return redirect()->route('user.dashboard');
        } 

        return redirect()->back()->with('error', 'username or password is invalid');
    }

    public function logout() {
        Auth::guard('users')->logout();
        return redirect()->route('user.login');
    }

}
