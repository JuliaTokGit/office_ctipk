<?php
if (!empty($filters['id'])){
    $order=Order::find($filters['id']);
    if (!$order){
     die(header ("Location: ".$path['base']."/orders"));
    }
    
    if (isset($_POST['action']) && $_POST['action']=='edit'){
        unset($_POST['action']);
        $order->fill($_POST);
        $order->save();    
    }    

    $context['order']=$order;    
}else{
  die(header ("Location: ".$path['base']."/orders"));
}

foreach ($page->properties->forms as &$form) {    
    foreach ($form->fields as &$field) {
        $field->{$field->type} = $field->property;
        $field->data = (object)['name' => 'obj', 'value' => $field->property];
        if(!empty($order->{$field->property})){
            $field->value=$order->{$field->property};
        }
        $form->mod_fields[] = $field;
    }
    $form->fields=$form->mod_fields;
}