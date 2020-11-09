<?php
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OrderPerformer extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Исполнители_Заявка';
    protected $primaryKey = 'Код_Исполнителя';
    public $incrementing = false;
    protected $title = 'Исполнители по видам работ';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Исполнителя'];
    public $timestamps = false;
    public $appends=['id'];

    public function getIdAttribute(){
        return $this->Код_Исполнителя;
    }

    public function setIdAttribute($value){
        $this->attributes['Код_Исполнителя']=$value;
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($order_performer) {
            $order_performer->Код_Исполнителя=OrderPerformer::max('Код_Исполнителя')+1;            
        });

    }
}
