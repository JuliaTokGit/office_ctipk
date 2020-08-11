<?php
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Заявки';
    protected $primaryKey = 'Код_Заявки';
    protected $title = 'Заказ';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Заявки'];
    public $timestamps = false;
    protected $dates = [
        'Дата_Заявки',
        'Дата_Рождения',
        'Дата_Оплата',
        'Дата_Доплата',
        'Дата_Техник',
        'Дата_Исполнения',
        'Дата_Задания',
        'Дата_Архив',
        'Дата_Оплаты_Дог',
        'Дата_Выдачи',
        'Дата_Выдачи_Заказчику',
        'Дата_Договора',
        'Дата_Переноса',
    ];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function getДатаЗаявкиAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function getДатаРожденияAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function getДатаОплатаAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function getДатаДоплатаAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function getДатаТехникAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function getДатаИсполненияAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function getДатаЗаданияAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function getДатаАрхивAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function getДатаОплатыДогAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }
    
    public function getДатаВыдачиAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function getДатаВыдачиЗаказчикуAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function getДатаДоговораAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public function getДатаПереносаAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }
}
