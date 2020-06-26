<?php

$form=Form::find($filters['id']);
if (!$form){
 die(header ("Location: ".$path['base']."/forms"));
}

$context['form']=$form;