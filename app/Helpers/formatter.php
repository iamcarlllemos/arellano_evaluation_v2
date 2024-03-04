<?php

if (!function_exists('to_ordinal')) {
    function to_ordinal($number, $string)
    {
        if (!is_numeric($number)) {
            return $number;
        }

        if ($number % 100 >= 11 && $number % 100 <= 13) {
            $suffix = 'th';
        } else {
            switch ($number % 10) {
                case 1:
                    $suffix = 'st';
                    break;
                case 2:
                    $suffix = 'nd';
                    break;
                case 3:
                    $suffix = 'rd';
                    break;
                default:
                    $suffix = 'th';
                    break;
            }
        }

        return ucwords($number . $suffix . ' ' . $string);
    }
}

if(!function_exists('to_status')) {
    function to_status($number) {
        if(!is_numeric($number)) {
            return $number;
        }

        $status = '';

        switch($number) {
            case 0: 
                $status = '
                <span class="px-4 p-2 inline-flex justify-center items-center bg-blue-100 text-blue-800 text-xs font-medium rounded-full dark:bg-blue-900 dark:text-blue-300">
                    <span class="w-2 h-2 me-1 bg-blue-500 rounded-full"></span>
                    Not Opened Yet
                </span>
                ';
                break;
            case 1:
                $status = '
                <span class="px-4 p-2 inline-flex justify-center items-center bg-orange-100 text-orange-800 text-xs font-medium rounded-full dark:bg-orange-900 dark:text-orange-300">
                    <span class="w-2 h-2 me-1 bg-orange-500 rounded-full"></span>
                    On Going
                </span>
                ';
                break;
            case 2:
                $status = '
                <span class="px-4 p-2 inline-flex justify-center items-center bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full dark:bg-yellow-900 dark:text-yellow-300">
                    <span class="w-2 h-2 me-1 bg-yellow-500 rounded-full"></span>
                    Closed
                </span>
                ';
                break;
            case 3:
                $status = '
                <span class="px-4 p-2 inline-flex justify-center items-center bg-red-100 text-red-800 text-xs font-medium rounded-full dark:bg-red-900 dark:text-red-300">
                    <span class="w-2 h-2 me-1 bg-red-500 rounded-full"></span>
                    Finished
                </span>
                ';
                break;
            default:
                $status = 'error occured';
        }


        return $status;

    }
}

if(!function_exists('read_more')) {
    function read_more($text) {
        $length = 250;
        $first = '';
        $last = '';
    
        if (strlen($text) > $length) {
            $first = substr($text, 0, $length);
            $last = substr($text, $length);
            echo '
                <div wire:ignore>
                    <p class="mb-0 parent-text">' . $first . '<span class="read-more-ellipsis">...</span><span class="hidden">' . $last . '</span> <a href="javascript:void(0)" class="text-blue-500 text-xs font-bold hover:underline see-more">Read More</a></p>
                </div>
            ';
        } else {
            echo '<p class="mb-0 parent-text">' . $text . '</p>';
        }
    }
    
}