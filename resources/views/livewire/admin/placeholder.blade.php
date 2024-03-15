@if(in_array($form['action'], ['create', 'update', 'delete']))
<div>
    <div class="w-100 flex justify-between items-center gap-2 mt-8s animate-pulse">
        <div>
            <div class="h-[30px] w-[200px] bg-gray-200 rounded-lg dark:bg-gray-700"></div>
            <div class="h-[15px] w-[180px] bg-gray-200 rounded-lg dark:bg-gray-700 mt-2"></div>
            <div class="h-[35px] w-[95px] bg-gray-200 rounded-lg dark:bg-gray-700 mt-3"></div>
        </div>
    </div>
    <div class="grid grid-cols-12 mt-10">
        <div class="col-span-12">
            <div role="status" class="flex justify-center w-100 bg-gray-300 rounded-lg animate-pulse dark:bg-gray-700">
                <div class="w-full p-4 mt-5">
                    <div class="h-[20px] bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
                    <div class="h-[20px] bg-gray-200 rounded-full dark:bg-gray-700 max-w-[480px] mb-2.5"></div>
                    <div class="h-[20px] bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
                    <div class="h-[20px] bg-gray-200 rounded-full dark:bg-gray-700 max-w-[440px] mb-2.5"></div>
                    <div class="h-[20px] bg-gray-200 rounded-full dark:bg-gray-700 max-w-[460px] mb-2.5"></div>
                    <div class="h-[20px] bg-gray-200 rounded-full dark:bg-gray-700 max-w-[360px]"></div>
                    <div class="h-[20px] bg-gray-200 rounded-full dark:bg-gray-700 max-w-[480px] mb-2.5"></div>
                    <div class="h-[20px] bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
                    <div class="h-[20px] bg-gray-200 rounded-full dark:bg-gray-700 max-w-[440px] mb-2.5"></div>
                    <div class="h-[20px] bg-gray-200 rounded-full dark:bg-gray-700 max-w-[480px] mb-2.5"></div>
                    <div class="h-[20px] bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
                </div>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</div>
@else
<div class="block">
    <div class="w-100 flex justify-between items-center gap-2 mt-3 animate-pulse">
        <div>
            <div class="h-[35px] w-[95px] bg-gray-200 rounded-lg dark:bg-gray-700"></div>
        </div>
        <div>
            <div class="h-[40px] w-52 bg-gray-200 rounded-lg dark:bg-gray-700"></div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-3 mt-14">
        @php
            $pick = rand(1, 10);
            for($i = 0; $i <= $pick; $i++) {
                echo '
                <div class="col-span-4">
                    <div role="status" class="flex items-center justify-center h-56 max-w-sm bg-gray-300 rounded-lg animate-pulse dark:bg-gray-700">
                        <svg class="w-10 h-10 text-gray-200 dark:text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                        <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
                        <path d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM9 13a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2Zm4 .382a1 1 0 0 1-1.447.894L10 13v-2l1.553-1.276a1 1 0 0 1 1.447.894v2.764Z"/>
                    </svg>
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                ';
            }
        @endphp
    </div>
</div>

@endif

