<?php
use Illuminate\Database\Eloquent\Model;
use CastDate;

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
    ];
    // protected $dateFormat = 'Y-m-d H:i:s.v';
    protected $casts = [
        'Дата_Заявки' => 'CastDate::class',
    ];
    // public function getDateFormat()
    // {
    //     return 'Y-d-m H:i:s.v';
    // }

}
