<?php
use Illuminate\Database\Eloquent\Model;

class OrderWork extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Работы_Заявки';
    public $primaryKey = 'Код_Работы';
    public $incrementing = false;
    protected $title = 'Таблица работы заявки';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Работы'];
    public $timestamps = false;
    public $relations = ['inventarisation'];
    public $appends=['id'];

    public function inventarisation()
    {
        return $this->belongsTo('Inventarisation','КО');
    }


    public function getIdAttribute(){
        return $this->Код_Работы;
    }

    public function setIdAttribute($value){
        $this->attributes['Код_Работы']=$value;
    }

    public function setКодСправочникаAttribute($value){
        $this->attributes['Код_Справочника']=$value;
        $this->attributes['Наименование']=Work::find($value)->Наименование_Видов_Работ;
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($order_work) {
            $order_work->Код_Работы=OrderWork::max('Код_Работы')+1;            
        });

    }
}
