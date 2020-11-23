<?php
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Carbon\Carbon;

class CustomDate implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {      
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function set($model, $key, $value, $attributes)    
    {                
        if (empty($value)){
            $result=null;
        }else{
            $result=Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d H:i:s.v');
        }
        return [$key => $result];
    }
}