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
                    <span class="text-gray-900">Students</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-3xl font-semibold">All Students</h1>
        <p class="text-sm font-medium mt-1 text-slate-900">Lists of all student accounts created.</p>
        <div class="flex justify-end">
          @livewire('search')
        </div>
        <div class="grid grid-cols-12 gap-3 mt-5">
          <div class="col-span-4 bg-slate-800 shadow-lg rounded-lg text-white relative">
            <div class="absolute top-4 right-4 z-1 cursor-pointer">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
              </svg>              
            </div>
            <div class="p-5 text-left">
              <img src="{{asset('storage/images/logo.png')}}" alt="" srcset="" class="w-24">
              <div class="mt-1">
                <h1 class="text-2xl font-extrabold">Carl Llemos</h1>
                <h5 class="text-sm font-semibold text-slate-400">@carl01</h5>
                <h5 class="text-sm font-semibold text-slate-400">iamcarlllemos@gmail.com</h5>
              </div>
            </div>
          </div>
          <div class="col-span-4 bg-slate-800 shadow-lg rounded-lg text-white relative">
            <div class="absolute top-4 right-4 z-1 cursor-pointer">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
              </svg>              
            </div>
            <div class="p-5 text-left">
              <img src="{{asset('storage/images/logo.png')}}" alt="" srcset="" class="w-24">
              <div class="mt-1">
                <h1 class="text-2xl font-extrabold">Carl Llemos</h1>
                <h5 class="text-sm font-semibold text-slate-400">@carl01</h5>
                <h5 class="text-sm font-semibold text-slate-400">iamcarlllemos@gmail.com</h5>
              </div>
            </div>
          </div>
          <div class="col-span-4 bg-slate-800 shadow-lg rounded-lg text-white relative">
            <div class="absolute top-4 right-4 z-1 cursor-pointer">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
              </svg>              
            </div>
            <div class="p-5 text-left">
              <img src="{{asset('storage/images/logo.png')}}" alt="" srcset="" class="w-24">
              <div class="mt-1">
                <h1 class="text-2xl font-extrabold">Carl Llemos</h1>
                <h5 class="text-sm font-semibold text-slate-400">@carl01</h5>
                <h5 class="text-sm font-semibold text-slate-400">iamcarlllemos@gmail.com</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

@endsection