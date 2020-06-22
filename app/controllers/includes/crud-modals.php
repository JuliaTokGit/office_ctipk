<?php

$crud=$page->crud_modals;

$create=['title'=>$crud['titles']['create'],'id'=>'modalCreate'];
$edit=['title'=>$crud['titles']['edit'],'id'=>'modalEdit'];
$delete=['title'=>$crud['titles']['delete'],'subheader'=>$crud['subheaders']['delete'],'id'=>'modalDelete'];

$create['body']=[
	'data'=>[['name'=>'done', 'value'=>'create']],
	'class'=>'ajax-form',
	'fields'=>[['hidden'=>'action', 'value'=>'create']],
	'buttons'=>[[
		'type'=>'submit',
		'text'=>'Create',
		'class'=>'btn-complete'
	]],
];

$edit['body']=[
	'data'=>[['name'=>'done', 'value'=>'edit']],
	'class'=>'ajax-form',
	'fields'=>[['hidden'=>'action', 'value'=>'edit']],
	'buttons'=>[[
		'type'=>'submit',
		'text'=>'Save',
		'class'=>'btn-primary'
	]],
];

$delete['body']=[
	'data'=>[['name'=>'done', 'value'=>'del']],
	'class'=>'ajax-form',
	'fields'=>[['hidden'=>'action', 'value'=>'delete']],
	'buttons'=>[[
		'type'=>'submit',
		'text'=>'Delete',
		'class'=>'btn-danger'
	]],
];


$edit['body']['fields'][]=[
	'hidden'=>'id',
	'data'=>['name'=>'obj','value'=>'id']
];

$delete['body']['fields'][]=[
	'hidden'=>'id',
	'data'=>['name'=>'obj','value'=>'id']
];

foreach ($crud['fields'] as $field) {
	$field[$field['type']]=$field['property'];
	$create['body']['fields'][]=$field;
	$field['data']=['name'=>'obj','value'=>$field['property']];
	$edit['body']['fields'][]=$field;
}

unset($page->crud_modals);
unset($context['page']->properties->crud_modals);

if (!isset($context['page']->properties->modal)){
	$context['page']->properties->modal=[];
}

$context['page']->properties->modal[]=$create;
$context['page']->properties->modal[]=$edit;
$context['page']->properties->modal[]=$delete;
// print_r($context);
