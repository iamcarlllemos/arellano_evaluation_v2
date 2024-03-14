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
        @livewire($data['livewire']['component'], $data['livewire']['data'])
    </div>
    <div class="fixed flex md:hidden items-center justify-center left-5 bottom-5 bg-slate-900 w-[30px] h-[30px] rounded-md z-50">
        <button type="button" data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar" class="hamburger cursor-pointer text-sky-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
    </div>
   
</div>

@endsection