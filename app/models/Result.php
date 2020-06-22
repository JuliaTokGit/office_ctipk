<?php
use Jenssegers\Mongodb\Eloquent\Model;

class Result extends Model
{    
    protected $title = 'Результаты';
    protected $connection = 'mongodb';
    protected $guarded = ['id'];
    public $relations = ['upload','request'];
    protected $appends=['id'];

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function upload()
    {
        return $this->belongsTo('Upload');
    }

    public function request()
    {
        return $this->belongsTo('Request');
    }

    public function updateValuations()
    {            
        $row = 0;
        $start = 2;
        $request=Request::find($this->request_id);
        if (($handle = fopen($this->upload->full_path, 'r')) !== FALSE) {
            while (($entry = fgetcsv($handle, 0, ';')) !== FALSE) {
                $row++;                
                if ($row>$start && $entry[0]!=''){
                    $data = array_map(function($val) { 
                        return iconv('CP1251', 'UTF-8', $val); 
                    }, $entry);
                                        
                    $val=Valuation::firstOrCreate(['cadastral_number'=>$data[0],'request_id'=>$request->id]);
                    $val->result_id=$this->id;
                    $val->data=$data;                    
                    $val->save();
                    
                    $parcel=$request->parcels()->where('cadastral_number',$data[0])->update(['valuation_id'=>$val->id]);                    
                }
            }
            fclose($handle);
        }                
    }

    protected static function boot()
    {
        parent::boot();
        self::saved(function($result)
        {
            $result->updateValuations();
        });

        self::deleted(function($result)
        {
            $result->valuations()->delete();
        });
        
    }

}
