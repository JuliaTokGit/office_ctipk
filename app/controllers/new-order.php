<?php

$order=new Order();
$order->state=0;
$order->stage=0;
$order->save();

exit(header ("Location: ".$path['base']."/order/id/".$order->Код_Заявки));