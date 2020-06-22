<?php
use Jenssegers\Mongodb\Eloquent\Model;

class Estate extends Model
{
    protected $title = 'Объекты недвижимости';
    protected $connection = 'mongodb';
    protected $guarded = ['id'];
    

    public function result()
    {
        return $this->belongsTo('Result');
    }

    public function source()
    {
        return $this->belongsTo('Source');
    }

    public function request()
    {
        return $this->source->request();
    }
}
