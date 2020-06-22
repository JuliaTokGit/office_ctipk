<?php

$url=$site->protocol.'://'.$site->domain.'/'.$_POST['page'];

unset($_POST['page']);

foreach ($_POST as $key => $value) {
	if ($value!='') $url.='/'.$key.'/'.$value;
	
}
header ("Location: ".$url);
die();