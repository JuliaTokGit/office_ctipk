<?php
use Jenssegers\Mongodb\Eloquent\Model;

class Request extends Model
{
    protected $title = 'Запросы'; 
    protected $connection = 'mongodb';   
    protected $guarded = ['id'];
    public $relations = ['results','sources'];
    protected $appends=['id','appraised','proportion'];
    // protected $appends=['appraised'];

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function sources()
    {
        return $this->hasMany('Source');
    }

    public function results()
    {
        return $this->hasMany('Result');
    }

    public function valuations()
    {
        return $this->hasMany('Valuation');
    }

    public function parcels()
    {
        return $this->hasMany('Parcel');
    }

    public function getProportionAttribute()
    {
        return $this->appraised.' / '.$this->quantity;
    }

    public function getAppraisedAttribute()
    {
        return $this->parcels()->where('valuation_id','exists',true)->count();
    }

    public function calculateQuantity()
    {
        $this->attributes['quantity']=0;
        // foreach ($this->sources as $source) {
            // dd($this->attributes);
            $this->attributes['quantity']=$this->sources->sum('quantity');
        // }
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();
        self::saving(function($request)
        {
            $last_num=Request::max('number');
            
            if (is_null($last_num)){
                $request->number=1000;            
            }else{
                $request->number=++$last_num;
            }            
        });

        self::deleted(function($request)
        {
            $request->sources()->delete();
            $request->results()->delete();
        });
    }
}
