<?php

$new_id=Order::max('Код_Заявки')+1;

$order=new Order();
$order->Код_Заявки=$new_id;
$order->save();

// $order=Order::find(85420);
// $order->Вид_Заказчик=2

dd($new_id);
