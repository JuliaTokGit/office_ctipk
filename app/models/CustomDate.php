<?php
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Carbon\Carbon;

class CustomDate implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {      
        $value=str_replace('T',' ',$value);
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function set($model, $key, $value, $attributes)    
    {                
        if (empty($value)){
            return [$key => null];
            // $result=null;
            
        }else{
            // $timezone = new DateTimeZone("UTC");
            // $date = new DateTime("now", $timezone);
            // $result=$date->format("Y-m-d\TH:i:s");
            // $result=;
            // dd(Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d\TH:i:s.v'));
            return [$key => Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d\TH:i:s.v')];
        }
        
    }
}