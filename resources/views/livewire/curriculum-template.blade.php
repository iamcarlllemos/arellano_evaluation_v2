<div>
    @if($form['action'] === 'create')
        <h1 class="text-3xl font-semibold">{{$form['title']['create']}}</h1>
        <p class="text-sm font-medium mt-1 text-slate-900">{{$form['subtitle']['create']}}</p>
        <div class="w-100 flex justify-between items-center gap-2 mb-10">
            <div class="mt-[29px]">
                <a wire:navigate href="{{route('linking.curriculum-template')}}" class="bg-slate-900 py-2 px-6 text-white text-sm font-bold rounded-md">Go Back</a>
            </div>
        </div>
        @include('components.alert')
        <div class="m-auto relative max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 mt-[50px]">
                <div class="flex items-center justify-between  md:p-5 border-b rounded-t dark:border-gray-600">
                    <div class="block">
                        <p class="text-sm text-wslate-600 font-bold">Note: All <span class="text-red-900">*</span> is required.</p>
                    </div>
                </div>
                <form wire:submit="create" class="p-4 md:p-5">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2"  wire:ignore.self>
                            <label class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">Department <span class="text-red-900">*</span></label>
                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model='department_id' wire:change='loadDepartments($event.target.value)'>
                                @if(count($departments) > 0)
                                    <option value=""> - CHOOSE - </option>
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                @else
                                    <option value=""> - CREATE DEPARTMENT FIRST - </option>
                                @endif
                            </select>
                            @error($department_id)
                                <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="col-span-2"  wire:ignore.self>
                            <label class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">Course <span class="text-red-900">*</span></label>
                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model='course_id' wire:change='loadCourses($event.target.value)'>
                                @if(count($courses) > 0)
                                    <option value=""> - CHOOSE - </option>
                                    @foreach($courses as $course)
                                        <option value="{{$course->id}}">{{'(' . $course->code . ') - ' . $course->name}}</option>
                                    @endforeach
                                @else
                                    <option value=""> - CHOOSE DEPARTMENT FIRST - </option>
                                @endif
                            </select>
                            @error($course_id)
                                <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="col-span-2"  wire:ignore.self>
                            <label class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">Subject <span class="text-red-900">*</span></label>
                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model='subject_id' wire:change='loadCourses($event.target.value)'>
                                @if(count($subjects) > 0)
                                    <option value=""> - CHOOSE - </option>
                                    @foreach($subjects as $subject)
                                        <option value="{{$subject->id}}">{{'(' . $subject->code . ') - ' . $subject->name}}</option>
                                    @endforeach
                                @else
                                    <option value=""> - CHOOSE DEPARTMENT, COURSE FIRST - </option>
                                @endif
                            </select>
                            @error($subject_id)
                                <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                            @enderror
                        </div>
                        @foreach($form['data'] as $key => $item) 
                            @if(in_array($item['type'], ['text', 'email', 'password']))
                                <div class="col-span-2">
                                    <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">{{$item['label']}} <span class="text-red-900">*</span></label>
                                    <input type="{{$item['type']}}" wire:model="{{$key}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="{{$item['placeholder']}}">
                                    @error($key)
                                        <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                    @enderror
                                </div>
                            @elseif(in_array($item['type'], ['select']))
                                <div class="col-span-2"  wire:ignore.self>
                                    <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">{{$item['label']}} <span class="text-red-900">*</span></label>
                                    <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model.live="{{$key}}" wire:change='{{$item['on_change']}}($event.target.value)'>
                                        @if(count($item['options']['data']) > 0)
                                            @if($item['options']['is_from_db'])
                                                <option value=""> - CHOOSE - </option>
                                                @foreach($item['options']['data'] as $option_key => $options)
                                                    <option value="{{$options->id}}">{{$options->name}}</option>
                                                @endforeach
                                            @else
                                                <option value=""> - CHOOSE - </option>
                                                @foreach($item['options']['data'] as $option_key => $options)
                                                    <option value="{{$option_key}}">{{$options}}</option>
                                                @endforeach
                                            @endif
                                        @else
                                            <option value=""> - {{$item['options']['no_data']}} - </option>
                                        @endif
                                    </select>
                                    @error($key)
                                        <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                    @enderror
                                </div>
                            @elseif(in_array($item['type'], ['file']))
                                <div class="col-span-2">
                                    <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">{{$item['label']}} <span class="text-red-900">*</span></label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG or GIF (MAX. 5MB)</p>
                                            </div>
                                            <input id="dropzone-file" wire:model="{{$key}}" type="{{$item['type']}}" class="hidden" />
                                        </label>
                                    </div>                  
                                    <div wire:loading wire:target="{{$key}}">Uploading...</div>
                                    @if ($image && in_array($image->getClientOriginalExtension(), ['png', 'jpg', 'jpeg']))
                                        <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase mt-5" style="font-size: 12px">Image Preview</label>
                                        <img src="{{ $image->temporaryUrl() }}" class="w-[200px] h-[150px] object-cover object-center rounded-lg">    
                                    @endif
                                    @error($key)
                                        <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                    @enderror
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="flex justify-end mt-10">
                        <button id="form-button" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Proceed
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @elseif($form['action'] === 'update') 
        <h1 class="text-3xl font-semibold">{{$form['title']['update']}}</h1>
        <p class="text-sm font-medium mt-1 text-slate-900">{{$form['subtitle']['update']}}</p>
        <div class="w-100 flex justify-between items-center gap-2 mb-10">
            <div class="mt-[29px]">
                <a wire:navigate href="{{route('linking.curriculum-template')}}" class="bg-slate-900 py-2 px-6 text-white text-sm font-bold rounded-md">Go Back</a>
            </div>
        </div>
        @include('components.alert')
        <div class="m-auto relative max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 mt-[50px]">
                <div class="flex items-center justify-between  md:p-5 border-b rounded-t dark:border-gray-600">
                    <div class="block">
                        <p class="text-sm text-slate-600 font-bold">Note: All <span class="text-red-900">*</span> is required.</p>
                    </div>
                </div>
                <form wire:submit="update" class="p-4 md:p-5">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        @foreach($form['data'] as $key => $item) 
                            @if(in_array($item['type'], ['text', 'email', 'password']))
                                <div class="col-span-2">
                                    <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">{{$item['label']}} <span class="text-red-900">*</span></label>
                                    <input type="{{$item['type']}}" wire:model="{{$key}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="{{$item['placeholder']}}" value="{{$name}}">
                                    @error($key)
                                        <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                    @enderror
                                </div>
                            @elseif(in_array($item['type'], ['select']))
                                <div class="col-span-2"  wire:ignore.self>
                                    <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">{{$item['label']}} <span class="text-red-900">*</span></label>
                                    <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model="{{$key}}">
                                        @if(count($item['options']['data']) > 0)
                                            @if($item['options']['is_from_db'])
                                                <option value=""> - CHOOSE - </option>
                                                @foreach($item['options']['data'] as $option_key => $options)
                                                    <option value="{{$options->id}}">{{$options->name}}</option>
                                                @endforeach
                                            @else
                                                <option value=""> - CHOOSE - </option>
                                                @foreach($item['options']['data'] as $option_key => $options)
                                                    <option value="{{$option_key}}">{{$options}}</option>
                                                @endforeach
                                            @endif
                                        @else
                                            <option value=""> - {{$item['options']['no_data']}} - </option>
                                        @endif
                                    </select>
                                    @error($key)
                                        <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                    @enderror
                                </div>
                            @elseif(in_array($item['type'], ['file']))
                                <div class="col-span-2">
                                    <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">{{$item['label']}} <span class="text-red-900">*</span></label>
                                    @if ($image && !method_exists($image, 'getClientOriginalExtension'))
                                        <img src="{{ asset('storage/images/branches/' . $image) }}" class="w-[200px] h-[150px] object-cover object-center rounded-lg">
                                    @elseif(session()->has('flash') && session('flash')['status'] == 'success')
                                        <img src="{{ asset('storage/images/branches/' . $image) }}" class="w-[200px] h-[150px] object-cover object-center rounded-lg">
                                    @endif       
                                    <div class="flex items-center justify-center w-full mt-3">
                                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG or GIF (MAX. 5MB)</p>
                                            </div>
                                            <input id="dropzone-file" wire:model="{{$key}}" type="{{$item['type']}}" class="hidden" />
                                        </label>
                                    </div>                  
                                    <div wire:loading wire:target="{{$key}}">Uploading...</div>
                                    @if ($image && method_exists($image, 'getClientOriginalExtension') && in_array($image->getClientOriginalExtension(), ['png', 'jpg', 'jpeg']))
                                        <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase mt-5" style="font-size: 12px">Image Preview</label>
                                        <img src="{{ $image->temporaryUrl() }}" class="w-[200px] h-[150px] object-cover object-center rounded-lg">    
                                    @endif                                
                                    @error($key)
                                        <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                    @enderror
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="flex justify-end mt-10">
                        <button id="form-button" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Proceed
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @elseif($form['action'] === 'delete')
        <h1 class="text-3xl font-semibold">{{$form['title']['delete']}}</h1>
        <p class="text-sm font-medium mt-1 text-slate-900">{{$form['subtitle']['delete']}}</p>
        <div class="w-100 flex justify-between items-center gap-2 mb-10">
            <div class="mt-[29px]">
                <a wire:navigate href="{{route('linking.curriculum-template')}}" class="bg-slate-900 py-2 px-6 text-white text-sm font-bold rounded-md">Go Back</a>
            </div>
        </div>
        @include('components.alert')
        <div class="m-auto relative max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 mt-[50px]">
                <div class="flex items-center justify-between  md:p-5 border-b rounded-t dark:border-gray-600">
                    <div class="block">
                        <h3 class="text-2xl font-extrabold">Are you sure to delete?</h3>
                        <p class="text-sm text-slate-600 font-bold">Note: All data connected to this branch will also be removed. Please be advised that this action is <span class="font-bold uppercase text-red-600">irreversable.</span></p>
                    </div>
                </div>
                <form wire:submit="delete" class="p-4 md:p-5">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        @foreach($form['data'] as $key => $item) 
                            @if(in_array($item['type'], ['text', 'email', 'password']))
                                <div class="col-span-2">
                                    <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">{{$item['label']}} <span class="text-red-900">*</span></label>
                                    <input type="{{$item['type']}}" wire:model="{{$key}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="{{$item['placeholder']}}" value="{{$name}}" disabled>
                                    @error($key)
                                        <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                    @enderror
                                </div>
                            @elseif(in_array($item['type'], ['select']))
                                <div class="col-span-2"  wire:ignore.self>
                                    <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">{{$item['label']}} <span class="text-red-900">*</span></label>
                                    <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model="{{$key}}" disabled>
                                        @if(count($item['options']['data']) > 0)
                                            @if($item['options']['is_from_db'])
                                                <option value=""> - CHOOSE - </option>
                                                @foreach($item['options']['data'] as $option_key => $options)
                                                    <option value="{{$options->id}}">{{$options->name}}</option>
                                                @endforeach
                                            @else
                                                <option value=""> - CHOOSE - </option>
                                                @foreach($item['options']['data'] as $option_key => $options)
                                                    <option value="{{$option_key}}">{{$options}}</option>
                                                @endforeach
                                            @endif
                                        @else
                                            <option value=""> - {{$item['options']['no_data']}} - </option>
                                        @endif
                                    </select>
                                    @error($key)
                                        <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                    @enderror
                                </div>
                            @elseif(in_array($item['type'], ['file']))
                                <div class="col-span-2">
                                    <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">{{$item['label']}} <span class="text-red-900">*</span></label>
                                    @if ($image && !method_exists($image, 'getClientOriginalExtension'))
                                        <img src="{{ asset('storage/images/branches/' . $image) }}" class="w-[200px] h-[150px] object-cover object-center rounded-lg">
                                    @endif       
                                    @error($key)
                                        <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                    @enderror
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="flex justify-end mt-10">
                        <button id="form-button" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Proceed
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <h1 class="text-3xl font-semibold">{{$form['title']['index']}}</h1>
        <p class="text-sm font-medium mt-1 text-slate-900">{{$form['subtitle']['index']}}</p>
        <div class="w-100 flex justify-between items-center gap-2 mt-5">
            <div>
                <a wire:navigate href="?action=create" class="bg-slate-900 py-2 px-6 text-white text-sm font-bold rounded-md">Create</a>
            </div>
            <div class="w-15 md:flex md:gap-3">
                {{-- <select wire:ignore.self wire:model.live='select' class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @if(count($data['branches']) > 0)
                        <option value=""> - All - </option>
                        @foreach($data['branches'] as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    @else
                        <option value="">Create a branch first.</option>
                    @endif
                </select> --}}
                <input wire:ignore.self type="search" wire:model.live="search" class="bg-transparent rounded-md" placeholder="Search here...">
            </div>
        </div>
        @if(session()->has('flash'))
            <div class="mt-10">
                @include('components.alert')
            </div>
        @endif
        {{-- DISPLAY --}}
        <div class="grid grid-cols-12 gap-3 mt-10">
            <div class="col-span-12">
                <div class="jstree">
                    <ul wire:poll>
                        @foreach ($data['templates']['results'] as $department)
                            <li>
                               <div class="inline-flex items-center gap-2.5 ms-[6px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"></path>
                                    </svg>
                                    {{ucwords($department['name'])}}
                               </div>
                                <ul>
                                    @foreach ($department['year'] as $year)
                                        <li>
                                            <div class="inline-flex items-center gap-2.5 ms-[6px]">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"></path>
                                                </svg>
                                                {{ucwords($year['name'])}}
                                            </div>
                                            <ul>
                                                @foreach ($year['course'] as $course)
                                                    <li>
                                                        <div class="inline-flex items-center gap-2.5 ms-[6px]">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"></path>
                                                            </svg>
                                                            {{ucwords($course['name'])}}
                                                        </div>
                                                        <ul>
                                                            @foreach ($course['semester'] as $semester)
                                                                <li>
                                                                    <div class="inline-flex items-center gap-2.5 ms-[6px]">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                                                                        </svg>                                                                          
                                                                        {{ucwords($semester['name'])}}
                                                                    </div>
                                                                    <ul>
                                                                        @foreach ($semester['subjects'] as $subject)
                                                                            <li data-jstree='{"type": "subject"}' data-template_id="{{$subject['id']}}" data-contextmenu="curriculum_template">
                                                                                <div class="flex items-center gap-2.5 ms-[6px]">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776"></path>
                                                                                    </svg>
                                                                                    {{ '(' . strtoupper($subject['code']) . ') - ' . ucwords($subject['name'])}} 
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif    
</div>