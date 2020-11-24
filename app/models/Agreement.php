<?php
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Agreement extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Соглашения';
    protected $primaryKey = 'Код_Соглашения';
    // public $incrementing = false;
    protected $title = 'Соглашение';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Соглашения'];
    public $timestamps = false;
    protected $casts = [
        'Дата_Оформл'=> CustomDate::class,
        'Дата_Исполн_Соглашения'=> CustomDate::class,
        'Дата_Исполн_Факт'=> CustomDate::class,        
    ];
    protected $dateFormat = 'Y-m-d H:i:s.v';
    public $appends=['id'];

    public function getIdAttribute($value){
        return $this->Код_Соглашения;
    }

    public function setIdAttribute($value){
        $this->attributes['Код_Соглашения']=$value;
    }

}
