$(document).ready(function () {
    $('.datepicker').datepicker({format: 'dd-mm-yyyy',todayHighlight:true, language: 'ru'}).on('changeDate', function(e){
        $(this).datepicker('hide');
    });
});