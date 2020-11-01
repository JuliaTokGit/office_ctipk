<?php
use Illuminate\Database\Eloquent\Model;

class OrderTransfer extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Перенос_Заявки';
    protected $primaryKey = 'Код_Переноса';
    protected $title = 'Перенос заявки';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Переноса'];
    public $timestamps = false;
    protected $dates = [
        'Дата_Переноса',
        'Дата_Служебной',
    ];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function getДатаПереносаAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }
    public function getДатаСлужебнойAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }
}
