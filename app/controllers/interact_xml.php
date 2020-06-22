<?php
use Ramsey\Uuid\Uuid;

$request=Request::find($filters['request_id']);

// dd($request);
$context['request']=$request;
$context['vals']=$request->valuations()->where('have_changed','!=','без изменений')->get();
// dd($context['vals']);
$context['date']=date("Y-m-d");
$guid= Uuid::uuid4();
$context['guid']=$guid;
header('Content-Type: application/xml');
header('Content-Disposition: attachment;filename="interact_entry_realty_'.$guid.'.xml"');
header('Cache-Control: max-age=0');