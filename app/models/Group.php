<?php
use Jenssegers\Mongodb\Eloquent\Model;

class Group extends Model
{
    protected $title = 'Группы';
    protected $connection = 'mongodb';
    protected $guarded = ['guarded'];
    

    public function valuations()
    {
        return $this->hasMany('Valuation');
    }

}
