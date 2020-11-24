<?php
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OrderTransfer extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Перенос_Заявки';
    protected $primaryKey = 'Код_Переноса';    
    protected $title = 'Перенос заявки';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Переноса'];
    public $timestamps = false;
    protected $casts = [
        'Дата_Переноса'=> CustomDate::class,
        'Дата_Служебной'=> CustomDate::class,
    ];
    protected $dateFormat = 'Y-m-d H:i:s.v';    
    public $appends=['id'];

    public function getIdAttribute(){
        return $this->Код_Переноса;
    }

    public function setIdAttribute($value){
        $this->attributes['Код_Переноса']=$value;
    }
    
}
