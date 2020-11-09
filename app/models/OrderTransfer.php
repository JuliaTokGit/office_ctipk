<?php
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OrderTransfer extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Перенос_Заявки';
    protected $primaryKey = 'Код_Переноса';
    public $incrementing = false;
    protected $title = 'Перенос заявки';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Переноса'];
    public $timestamps = false;
    protected $dates = [
        'Дата_Переноса',
        'Дата_Служебной',
    ];
    protected $casts = [
        'Дата_Переноса'=> 'date:Y-m-d',
        'Дата_Служебной'=> 'date:Y-m-d',
    ];
    protected $dateFormat = 'Y-m-d H:i:s.v';
    public $appends=['id'];

    public function getIdAttribute(){
        return $this->Код_Переноса;
    }

    public function setIdAttribute($value){
        $this->attributes['Код_Переноса']=$value;
    }

    public function getДатаПереносаAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }
    public function getДатаСлужебнойAttribute($value){
        return empty($value)?'':Carbon::createFromFormat('Y-m-d H:i:s.v', $value)->format('Y-m-d');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($order_transfer) {
            $order_transfer->Код_Переноса=OrderTransfer::max('Код_Переноса')+1;            
        });

    }
}
