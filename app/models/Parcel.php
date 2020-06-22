<?php
use Jenssegers\Mongodb\Eloquent\Model;

class Parcel extends Model
{
    protected $title = 'Объекты';
    protected $connection = 'mongodb';
    // protected $fillable = [
    //   'result_id','source_id','cadastral_number','type','cadastral_cost','specific_cadastral_cost','determination_date','usage','method','group_id'
    // ];
    protected $guarded = ['id'];

    public function valuation()
    {
        return $this->hasOne('Valuation');
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
