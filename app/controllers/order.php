<?php
if (!empty($filters['id'])){
    $order=Order::find($filters['id']);
    if (!$order){
     die(header ("Location: ".$path['base']."/orders"));
    }
    
    if (isset($_POST['action']) && $_POST['action']=='edit'){
        unset($_POST['action']);
        $order->fill($_POST);        
        if($order->state==$order->stage){
            $order->stage=$order->stage+1;
        }
        if (!isset($_POST['state'])) {
            $order->state=$order->stage;
        }
        $order->save();
    }    

    $context['order']=$order; 
    $page->properties->datatables->default->dataUrl.='/doc_id/'.$filters['id'];      
}else{
  die(header ("Location: ".$path['base']."/orders"));
}


foreach ($page->properties->forms as $key=>&$form) {
    $form->disabled=true;
    foreach ($form->fields as &$field) {        
        $field->{$field->type} = $field->property;
        $field->data = (object)['name' => 'obj', 'value' => $field->property];
        if(!empty($order->{$field->property})){
            $field->value=$order->{$field->property};
            if ($field->type=='radio'){
                foreach ($field->options as &$option) {
                    if ($option->value==$field->value){
                        $option->checked=true;
                    }else{
                        $option->checked=false;
                    }
                }
            }
        }
        $form->mod_fields[] = $field;
    }
    $form->fields=$form->mod_fields;
    if ($form->step==$order->state){
        $form->disabled=false;
        $form->bordered=true;
    }
    if ($form->step>$order->stage){
        unset($page->properties->forms[$key]);
    }
    
}
