$(document).ready(function(){
    $(".datepicker-days table").on('click',"td[data-action='selectDay']",function(){
        var day = $(this).data('day');
        window.location.href = "/doctracker?calendar="+day;
    });
});