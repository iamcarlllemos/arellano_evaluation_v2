@extends('layouts.app')
@section('content')

<div class="px-5">
    <div class="mt-5">
        <nav class="text-xs font-medium mb-2">
            <ol class="list-none p-0 inline-flex mt-4">
                <li class="flex items-center">
                    <a href="#" class="text-gray-500">Dashboard</a>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </li>
                <li class="flex items-center">
                    <a href="#" class="text-gray-500">Accounts</a>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </li>
                <li class="flex items-center">
                    <span class="text-gray-900">Branches</span>
                </li>
            </ol>
        </nav>
        @livewire('school-year', [
            'lazy' => false,
            'form' => [
                'title' => [
                    'index' => 'All School Years',
                    'create' => 'Create School Year',
                    'update' => 'Update School Year',
                    'delete' => 'Delete School Year'
                ],
                'subtitle' => [
                    'index' => 'List of all school years created.',
                    'create' => 'Create or add new school year.',
                    'update' => 'Apply changed to selected school year.',
                    'delete' => 'Permanently delete selected school year'
                ],
                'action' => $data['action'],
                'data' => [
                    'name' => [
                        'label' => 'Description',
                        'type' => 'text',
                        'placeholder' => 'Type...',
                    ],
                    'start_year' => [
                        'label' => 'Start Year',
                        'type' => 'select',
                        'placeholder' => 'Type...',
                        'options' => [
                            '2024' => 2024,
                            '2025' => 2025,
                            '2026' => 2026,
                            '2027' => 2027,
                            '2028' => 2028,
                            '2029' => 2029,
                            '2030' => 2030,
                        ]
                    ],
                    'semester' => [
                        'label' => 'Semester',
                        'type' => 'select',
                        'placeholder' => 'Type...',
                        'options' => [
                            '1' => '1st Semester',
                            '2' => '2nd Semester'
                        ]
                    ],
                ]
            ],
        ],)
    </div>
    
</div>

@endsection