<?php
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Agreement extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Соглашения';
    protected $primaryKey = 'Код_Соглашения';
    protected $title = 'Соглашение';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Соглашения'];
    public $timestamps = false;
    protected $dates = [
        'Дата_Оформл',
        'Дата_Исполн_Соглашения',
        'Дата_Исполн_Факт',
    ];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function getДатаОформлAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function getДатаИсполнСоглашенияAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function getДатаИсполнФактAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

}
