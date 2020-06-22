<?php
use Jenssegers\Mongodb\Eloquent\Model;

class Source extends Model
{
    protected $title = 'Исходник';
    protected $connection = 'mongodb';
    protected $guarded = ['guarded'];
    public $relations = ['upload','request'];
    protected $appends=['id'];
    protected $casts = ['quantity' => 'int'];

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function upload()
    {
        return $this->belongsTo('Upload');
    }

    public function parcels()
    {
        return $this->hasMany('Parcel');
    }

    public function request()
    {
        return $this->belongsTo('Request');
    }

    // public function libxml_display_error($error)
    // {       
    //     switch ($error->level) {
    //         case LIBXML_ERR_WARNING:
    //             $this->attributes['info'].= "Warning $error->code: ";
    //             break;
    //         case LIBXML_ERR_ERROR:
    //             $this->attributes['info'] .= "Error $error->code: ";
    //             break;
    //         case LIBXML_ERR_FATAL:
    //             $this->attributes['info'] .= "Fatal Error $error->code: ";
    //             break;
    //     }
    //     $this->attributes['info'] .= trim($error->message);
    //     if ($error->file) {
    //         $this->attributes['info'] .=    " in $error->file";
    //     }
    //     $this->attributes['info'] .= " on line $error->line\n";
    // }

    public function setUploadIdAttribute($value)
    {
        global $path;
        $this->attributes['upload_id']=$value;
        $upload=Upload::find($value);
        // libxml_use_internal_errors(true);

        // $xml = new DOMDocument();
        // $xml->load($file);
        // if ($xml->schemaValidate($path['xsd'].'ListForRating_v04.xsd')) {
            // dd('Успех!');
            if ($upload->content_type=='text/xml' && $xml = simplexml_load_string(file_get_contents($upload->full_path))){
                $data = json_decode(json_encode($xml));

                $this->attributes['info']='Успешно импортировано';
                $this->request->requested_at=$data->ListInfo->{'@attributes'}->DateForm;
                $this->attributes['guid']=$data->ListInfo->{'@attributes'}->GUID;
                $this->attributes['quantity']=$data->ListInfo->Quantity;
                $this->attributes['parcels_xml']=true;                            
            }
            
            // $data = simplexml_load_string(file_get_contents($file));
            // $this->request->requested_at=$data->ListInfo['DateForm'];
            // $this->attributes['guid']=$data->ListInfo['GUID'];
            // $this->attributes['quantity']=$data->ListInfo->Quantity;
            // $this->attributes['parcels_xml']=true;
        // }
    }


    public function createParcels($data)
    {        
        foreach ($data->Objects->Parcels->Parcel as $parcel) {
            $p=Parcel::firstOrCreate(['cadastral_number'=>$parcel->{'@attributes'}->CadastralNumber,'source_id'=>$this->id,'request_id'=>$this->request_id]);
            $p->data=$parcel;
            $p->save();
            $e=Estate::firstOrCreate(['cadastral_number'=>$parcel->{'@attributes'}->CadastralNumber]);
        }                
    }


    public function createEstates()
    {        
        $data = simplexml_load_string(file_get_contents($this->upload->full_path));
        foreach ($data->Objects->Parcels->Parcel as $parcel) {
           
        }        
    }


    protected static function boot()
    {
        parent::boot();
        self::saved(function($source)
        {
            if ($source->parcels_xml){
                $upload=Upload::find($source->upload_id);
                if ($xml = simplexml_load_string(file_get_contents($upload->full_path))){
                    $data = json_decode(json_encode($xml));
                    $source->createParcels($data);
                    $source->request->calculateQuantity();
                }                
                // $source->createEstates();                
            }
        });
        self::deleted(function($source)
        {
            if ($source->parcels_xml){
                $source->parcels()->delete();
                $source->request->calculateQuantity();
            }
        });
    }

}
