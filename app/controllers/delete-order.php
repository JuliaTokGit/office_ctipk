<?php
if (!empty($filters['id'])){
    $order=Order::find($filters['id']);
    if ($order){
     $order->delete();
    }


}
die(header ("Location: ".$path['base']."/orders"));
