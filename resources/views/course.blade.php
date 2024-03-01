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
        @livewire('course', [
            'lazy' => false,
            'form' => [
                'title' => [
                    'index' => 'All Course',
                    'create' => 'Create Course',
                    'update' => 'Update Course',
                    'delete' => 'Delete Course'
                ],
                'subtitle' => [
                    'index' => 'List of all courses created.',
                    'create' => 'Create or add new courses.',
                    'update' => 'Apply changed to selected course.',
                    'delete' => 'Permanently delete selected course'
                ],
                'action' => $data['action'],
                'data' => [
                    'department_id' => [
                        'label' => 'Department Name',
                        'type' => 'select',
                        'placeholder' => 'Type...',
                    ],
                    'code' => [
                        'label' => 'Code',
                        'type' => 'text',
                        'placeholder' => 'Type...',
                    ],
                    'name' => [
                        'label' => 'Name',
                        'type' => 'text',
                        'placeholder' => 'Type...',
                    ],
                ]
            ],
        ],)
    </div>
    
</div>

@endsection