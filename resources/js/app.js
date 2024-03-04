import './bootstrap';
import '@selectize/selectize/dist/css/selectize.default.css';
import '@selectize/selectize/dist/js/selectize.min.js'
import '/node_modules/flowbite/dist/flowbite.min.js'
import $ from 'jquery';

window.$ = $;
window.read_more = read_more;

$(function () { 
    dropdown();
    hideAlert();
    scrollTopError();

    $('.selectize').each(function () {
        const dataMaxItems = $(this).data('max-items');
        $(this).selectize({
            plugins: ["clear_button"],
            delimiter: " - ",
            persist: false,
            maxItems: dataMaxItems, 
        });
    });

    $('.selectize-input').addClass('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500');
});


function read_more(elem) {
    $(document).on('click', elem, function() {
        const $prevElement = $(this).prev();
        $prevElement.toggleClass('hidden');
    
        const readMoreEllipsis = $(this).prevAll('.read-more-ellipsis').first();
        
        if (!$prevElement.hasClass('hidden')) {
            readMoreEllipsis.addClass('hidden');
            $(this).text('Read Less');
        } else {
            readMoreEllipsis.removeClass('hidden');
            $(this).text('Read More');
        }
    });
    
    
}

function scrollTopError() {
    $('#form-button').on('click', () => {
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    })
}

function hideAlert() {
    $(document).on('click', '#close-alert', function() {
        $('#alert').fadeOut();
    });
}

function dropdown() {
    $(document).on('click', '#dropdown-button', function() {
        $('.dropdown').not($(this).next('.dropdown')).addClass('hidden');
        $(this).next('.dropdown').removeClass('hidden').css({
            position: 'absolute',
            inset: '0px auto auto 0px',
            margin: '0px',
            transform: 'translate3d(-140.5px, 34px, 0px)'
        });
    });

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#dropdown-button, .dropdown').length) {
            $('.dropdown').addClass('hidden');
        }
    });
}