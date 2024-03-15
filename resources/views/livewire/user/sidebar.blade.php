<aside id="sidebar" class="sidebar fixed top-0 left-0 z-40 w-[280px] md:w-[320px] h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar" style="background-color: #192231">
    <img src="{{asset('storage/images/logo-banner.png')}}" alt="" srcset="" class="px-3 w-100 h-[100px] md:h-[110px] mt-8 mb-5">
    @persist('scrollbar')
    <div class="overflow-y-auto py-2" wire:scroll style="height: calc(100vh - 160px)">
        <ul class="list ms-6">
            <li class="py-3">
                <label class="font-display text-xs text-slate-200 uppercase font-bold" style="font-size: 10px">Menu</label>
                <ul class="mt-2 ">
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="user.dashboard" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                                </svg>                                  
                            </div>                         
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Dashboard</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="py-3">
                <label class="font-display text-xs text-slate-200 uppercase font-bold" style="font-size: 10px">Settings</label>
                <ul class="mt-2">
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">My Profile</span>
                        </a>
                    </li>
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{ route('user.logout') }}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                </svg>
                            </div>                                                                                                                 
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Sign Out</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    @endpersist
 </aside>
