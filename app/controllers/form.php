<?php

$form=Form::find($filters['id']);
if (!$form){
 die(header ("Location: ".$path['base']."/forms"));
}

$context['form_data']=empty($form->data)?'[]':$form->data;

if (isset($_POST['action']) && $_POST['action']=='edit'){

    $form->data=$_POST['data'];
    $form->save();
    header ("Location: ".$path['base']."/forms");

}