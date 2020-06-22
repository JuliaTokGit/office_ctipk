<?php

use Carbon\Carbon;

setlocale(LC_ALL, 'ru_RU');
setlocale(LC_TIME, 'ru_RU');
Carbon::setLocale('ru');

if (isset($_POST['action']) && $_POST['action'] == 'set_range') {
    setcookie('Range', $_POST['range'], time() + 1800);
    $ranges = explode(' - ', $_POST['range']);
    $startdate = Carbon::createFromFormat('d-m-Y', $ranges[0]);
    $enddate = Carbon::createFromFormat('d-m-Y', $ranges[1]);
} elseif (isset($_COOKIE['Range'])) {
    $ranges = explode(' - ', $_COOKIE['Range']);
    $startdate = Carbon::createFromFormat('d-m-Y', $ranges[0]);
    $enddate = Carbon::createFromFormat('d-m-Y', $ranges[1]);
} else {
    $today = Carbon::today();
    $startdate = $today;

    if (isset($page->show_future_days)) {
        $days = (int) $page->show_future_days;
    } else {
        $days = 30;
    }

    $enddate = $today->copy()->addDays($days);
}

// print_r($context['page']);

$context['page']->properties->jsvars->range = $startdate->format('d-m-Y').' - '.$enddate->format('d-m-Y');

$context['page']->properties->jsvars->show_start = $startdate->format('d-m-Y');

$context['page']->properties->jsvars->show_end = $enddate->format('d-m-Y');
