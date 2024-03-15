<div>
    @if(in_array($form['action'], ['create', 'update', 'delete']))
        <h1 class="text-3xl font-semibold">{{$form[$form['action']]['title']}}</h1>
        <p class="text-sm font-medium mt-1 text-slate-900">{{$form[$form['action']]['subtitle']}}</p>
        <div class="w-100 flex justify-between items-center gap-2">
            <div class="mt-[29px]">
                <a wire:navigate href="{{route('admin.accounts.student')}}" class="bg-slate-900 py-2 px-6 text-white text-sm font-bold rounded-md">Go Back</a>
            </div>
        </div>
        @include('components.alert')
        <div class="m-auto relative max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 mt-[50px]">
                <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-600">
                    <div class="block">
                        <p class="text-sm text-wslate-600 font-bold">Note: All <span class="text-red-900">*</span> is required.</p>
                    </div>
                </div>
                <form wire:submit="{{$form['action']}}" class="p-4 md:p-5">
                    <div class="grid gap-4 mb-4 grid-cols-12">
                        @foreach($form[$form['action']]['data'] as $key => $item) 
                            @if(in_array($item['type'], ['text', 'email', 'date', 'password']))
                                <div class="{{$item['css']}}">
                                    <label for="{{$key}}" 
                                        class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" 
                                        style="font-size: 12px">
                                        {{$item['label']}} 
                                        {!!($item['required']) ? '<span class="text-red-900">*</span>' : ''!!}
                                    </label>
                                    <input type="{{$item['type']}}" wire:model="{{$key}}" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                                        placeholder="{{$item['placeholder']}}"
                                        {{($item['disabled']) ? 'disabled' : '' }}
                                        >
                                    @error($key)
                                        <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                    @enderror
                                </div>
                            @elseif(in_array($item['type'], ['select']))
                                <div class="{{$item['css']}}"  wire:ignore.self>
                                    <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">
                                        {{$item['label']}}
                                        {!!($item['required']) ? '<span class="text-red-900">*</span>' : ''!!}
                                    </label>
                                    <select 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                        wire:model="{{$key}}"
                                        {{($item['disabled']) ? 'disabled' : ''}}>
                                        @if(count($item['options']['data']) > 0)
                                            @if($item['options']['is_from_db'])
                                                <option value=""> - CHOOSE - </option>
                                                @foreach($item['options']['data'] as $option_key => $options)
                                                    @if (property_exists($options, $item['options']['group']))
                                                        <optgroup label="{{$options->name}}">
                                                            @foreach ($options->{$item['options']['group']} as $data)
                                                                <option value="{{$data->id}}">{{ucwords($data->name)}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @else
                                                        <option value="{{$options->id}}">{{ucwords($options->name)}}</option>
                                                    @endif
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
                                <div class="{{$item['css']}}">
                                    <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">
                                        {{$item['label']}}
                                        {!!($item['required']) ? '<span class="text-red-900">*</span>' : ''!!}
                                    </label>
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
                                            <input 
                                                id="dropzone-file" 
                                                wire:model="{{$key}}" 
                                                type="{{$item['type']}}" 
                                                class="hidden" 
                                                {{($item['disabled']) ? 'disabled' : ''}}
                                            />
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
                        <button 
                            id="form-button" 
                            class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            >
                            Proceed
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <h1 class="text-3xl font-semibold">{{$form['index']['title']}}</h1>
        <p class="text-sm font-medium mt-1 text-slate-900">{{$form['index']['subtitle']}}</p>
        <div class="w-100 block md:flex justify-between items-center gap-2 mt-5">
            <div>
                <a wire:navigate href="?action=create" class="bg-slate-900 py-2 px-6 text-white text-sm font-bold rounded-md">Create</a>
            </div>
            <div class="w-100 md:flex md:gap-3 mt-10 md:mt-0">
                <select wire:ignore.self wire:model.live='select' class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 w-full mb-5 md:mb-0">
                    @if(count($data['branches']) > 0)
                        @if(count($data['branches']) > 1)
                            <option value=""> - All - </option>
                        @endif
                        @foreach($data['branches'] as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    @else
                        <option value="">Create a branch first.</option>
                    @endif
                </select>
                <input wire:ignore.self type="search" wire:model.live="search" class="bg-transparent rounded-md w-full" placeholder="Search here...">
            </div>
        </div>
        @if(session()->has('flash'))
            <div class="mt-10">
                @include('components.alert')
            </div>
        @endif
        {{-- DISPLAY --}}
        <div wire:poll class="grid grid-cols-12 gap-3 mt-10">
            @if (count($data['students']) > 0)
                @foreach($data['students'] as $students)
                    <div class="col-span-12 md:col-span-4 bg-slate-100 shadow-lg rounded-lg text-dark relative overflow-hidden">                        
                        <div wire:ignore.self class="absolute z-10 top-5 right-3 text-teal-50">
                            <button id="dropdown-button" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                </svg>      
                            </button>       
                            <div wire:ignore.self id="drodown" class="dropdown z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                    <li>
                                        <a wire:navigate href="?action=update&id={{$students->id}}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Update</a>
                                    </li>
                                    <li>
                                        <a wire:navigate href="?action=delete&id={{$students->id}}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Delete</a>
                                    </li>
                                </ul>
                            </div>             
                        </div>
                        <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relatives">
                            <img class="rounded-lg w-full h-56 object-cover brightness-50" src="{{asset('storage/images/branches/' . $students['courses']['departments']['branches']->image)}}" alt="" />
                            <div class="p-5 absolute bottom-0 left-0">
                                <h5 class="text-2xl font-black tracking-tight text-white uppercase whitespace-break-spaces line-clamp-1">{{ucwords($students->firstname . ' ' . $students->lastname)}}</h5>
                                <p class="text-sm text-white font-bold line-clamp-2">{{'@' . $students->username}}</p>
                                <p class="text-sm text-white font-bold line-clamp-2">{{$students->email}}</p>
                            </div>
                            <div class="absolute top-6 border-2 border-sky-500 left-5 rounded-full text-slate-100 backdrop-blur-sm bg-white/30">
                                <img src="{{asset('storage/images/students/' . $students->image)}}" class="rounded-full w-[100px] h-[100px]">
                            </div>
                        </div>
                </div>
                @endforeach
            @else
            <div class="col-span-12">
                <div class="flex items-center p-4 mb-4 text-sm text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 dark:border-yellow-800" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                    <span class="font-medium">No records found.</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    @endif    
</div>
