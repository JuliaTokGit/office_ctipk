(function($) {

    'use strict';

    var r={};
    r['Год']=[moment(), moment().add(365, 'days')];
    r['60 дней']=[moment(), moment().add(60, 'days')];
    r['Месяц']=[moment(), moment().add(1, 'month')];
    r['Неделя']=[moment(), moment().add(6, 'days')];

    $("#range-calendar").daterangepicker({
        opens:'left',
        drops:'down',
        ranges: r,
        alwaysShowCalendars: true,
        locale: {
            "format": "DD-MM-YYYY",
            "separator": " - ",
            "applyLabel": "Применить",
            "cancelLabel": "Отмена",
            "fromLabel": "От",
            "toLabel": "До",
            "customRangeLabel": "Произвольно",
            "daysOfWeek": [
                "Вс",
                "Пн",
                "Вт",
                "Ср",
                "Чт",
                "Пт",
                "Сб"
            ],
            "monthNames": [
                "Январь",
                "Февраль",
                "Март",
                "Апрель",
                "Май",
                "Июнь",
                "Июль",
                "Август",
                "Сентябрь",
                "Октябрь",
                "Ноябрь",
                "Декабрь"
            ],
            "firstDay": 1
        }
    });
    
    $("#range-calendar").on('apply.daterangepicker', function(ev, picker) {          
        $(this).closest('form').submit();

    });
$('.open-range-calendar').on('click', function(event){
    $("#range-calendar").click();
});
    
})(window.jQuery);