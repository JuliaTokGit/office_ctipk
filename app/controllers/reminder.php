<?php
use Carbon\CarbonImmutable;

if (empty($filters['now'])) {
    $now=CarbonImmutable::now();
} else {
    $now=CarbonImmutable::createFromFormat('Y-m-d', $filters['now']);
}

$week=$now->addWeek();
$month=$now->addMonth();
$month_ago=$now->subMonth();


function checkForNew($collection)
{
    foreach ($collection as $key => &$sertificate) {
        $course_id=$sertificate->course_id;
        $new_learnings=Learning::where('student_id', $sertificate->student_id)->whereHas('order', function ($q) use ($course_id) {
            $q->where('course_id', $course_id);
        })->where('created_at', '>', $month_ago)->exists();
        if ($new_learnings) {
            $collection->forget($key);
        }
    }
}

$clients=Client::all();

foreach ($clients as $client) {
    $week_names='';
    $month_names='';
    $week_sertificates=$client->sertificates()->where('expire_date', '>', $now)->where('expire_date', '<=', $week)->get();
    $month_sertificates=$client->sertificates()->where('expire_date', '>', $week)->where('expire_date', '<=', $month)->get();

    checkForNew($week_sertificates);
    checkForNew($month_sertificates);

    foreach ($week_sertificates as $sertificate) {
        if (!empty($sertificate->student->email)) {

          $mail=new Emailer([
            'sertificate'=>$sertificate,
            'student'=>$sertificate->student,
            'course'=>$sertificate->course,
            'client'=>$sertificate->order->client
          ]);
          $mail->send('student_notify',$sertificate->student->email);

        }
        $week_names.=$sertificate->student->fullname.'<br>';
    }

    foreach ($month_sertificates as $sertificate) {
        $month_names.=$sertificate->student->fullname.'<br>';
    }

    if (empty($week_names)) {
        $month_pre='<p>Хочу напомнить вам, что в течение месяца заканчиваются удостоверения у следующих сотрудников '.$client->name.' :<br>';
        $week_pre='';
    } else {
        $week_pre='<p>Хочу напомнить вам, что в течение недели заканчиваются удостоверения у следующих сотрудников '.$client->name.' :<br>';
        $month_pre='</p><p>А в течение месяца – заканчиваются у сотрудников:<br>';
    }

    if (empty($month_names)) {
        $month_pre='';
    }

    if (!empty($week_names) || !empty($month_names)) {

      $mail=new Emailer([
        'week_pre'=>$week_pre,
        'week_names'=>$week_names,
        'month_pre'=>$month_pre,
        'month_names'=>$month_names
      ]);
      $mail->send('company_notify',$client->username);

    }
}
