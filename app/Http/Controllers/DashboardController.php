<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {

        $data = [
            'title' => '',
            'active' => '',
            'livewire' => [
                'component' => 'dashboard',
                'data' => []
            ]
        ];

        return view('template', compact('data'));
    }
}
