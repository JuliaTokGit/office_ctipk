<?php
$new_id=Order::max('Код_Заявки')+1;

$order=new Order();
$order->Код_Заявки=$new_id;
$order->save();

exit(header ("Location: ".$path['base']."/order/id/".$new_id));