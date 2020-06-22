<?php

$collection_name=$filters['collection'];

if (!in_array($collection_name, $cfg->listCollections())){
	die("error: Bad collection");
}

$collection=$cfg->{$collection_name};
$context['collection_name']=$collection_name;

if (isset($filters['item'])){
	get_or_save($collection,$filters['item']);
}else{
	$context['collection_list']=true;
	$context['page']->header='Конфиги коллекции : '.$collection_name;
	$context['configs']=(object)$collection->find();	 
}

function save_input($collection, $item, $data){
	$input = json_decode($data, TRUE);
	$collection->save($item, $input);
	echo '{"success":true}';
	die();
}

function get_or_save($collection, $item){
	global $context, $filters;
	if (isset($_POST['action']) && $_POST['action']=='save') save_input($collection, $item, $_POST['data']);
	$config=(object)$collection->findOne(array('_id'=>$item));
	$context['page']->header.=$config->_id;
	$context['id']=$config->_id;
	
	unset($config->_id);
	$context['config']=json_encode($config, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}