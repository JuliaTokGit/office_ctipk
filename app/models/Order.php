<?php
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Заявки';
    protected $primaryKey = 'Код_Заявки';
    public $incrementing = false;
    protected $title = 'Заказ';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Заявки'];
    public $timestamps = false;
    protected $dateFormat = '"Y-m-d H:i:s.v"';
    public $appends=['id'];

    protected $casts = [
        'Дата_Заявки'     => CustomDate::class,
        'Дата_Рождения'=> CustomDate::class,
        'Дата_Оплата'=> CustomDate::class,
        'Дата_Доплата'=> CustomDate::class,
        'Дата_Техник'=> CustomDate::class,
        'Дата_Исполнения'=> CustomDate::class,
        'Дата_Задания'=> CustomDate::class,
        'Дата_Архив'=> CustomDate::class,
        'Дата_Оплаты_Дог'=> CustomDate::class,
        'Дата_Выдачи'=> CustomDate::class,
        'Дата_Выдачи_Заказчику'=> CustomDate::class,
        'Дата_Договора'=> CustomDate::class,
        'Дата_Переноса'=> CustomDate::class,
    ];

    public function getIdAttribute($value){
        return $this->Код_Заявки;
    }

    public function setIdAttribute($value){
        $this->attributes['Код_Заявки']=$value;
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($order) {
            $order->Код_Заявки=Order::max('Код_Заявки')+1;            
        });

    }

}
