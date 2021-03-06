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
    $page->properties->datatables->default->crud_modals->fields[]=(object)[ "property"=>"Код_Док", "type"=>"hidden", "value"=>$filters['id']];
    
    // dd($page->properties->datatables->default->crud_modals);
}else{
  die(header ("Location: ".$path['base']."/orders"));
}

//$i=0;
foreach ($page->properties->forms as $key=>&$form) {
    $form->disabled=true;
    /*if ($i<2) {
    $form->buttons = (object)['type' => 'submit', 'class' => 'btn btn-success', 'text' => 'Далее'];
        }
    $i++;
    */
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
        if ($order->state==$order->stage){
            $form->buttons = (object)['type' => 'submit', 'class' => 'btn btn-success', 'text' => 'Далее'];
        }
    }

    if ($form->step>$order->stage){
        unset($page->properties->forms[$key]);

    }

    
}

include_once $path['controllers'].'includes/dt-crud-modals.php';