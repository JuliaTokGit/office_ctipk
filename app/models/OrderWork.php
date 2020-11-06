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
    public $relations = ['inventarisation','work'];
    public $appends=['id'];

    public function inventarisation()
    {
        return $this->belongsTo('Inventarisation','КО');
    }

    public function work()
    {
        return $this->belongsTo('Work','Код_Справочника');
    }

    public function getIdAttribute(){
        return $this->Код_Работы;
    }

    public function setIdAttribute($value){
        $this->attributes['Код_Работы']=$value;
    }
    
    public function setWorkКодГруппыAttribute($value){        
    }

    public function setКодСправочникаAttribute($value){
        $work=Work::find($value);        
        $this->attributes['Код_Справочника']=$value;
        $this->attributes['Наименование']=$work->Наименование_Видов_Работ;
        $this->attributes['Вид_Деятельности']=$work->Вид_Деятельности;
        $this->attributes['КО']=$work->КО;
        $this->attributes['Техник_Процент']=$work->Техник_Процент;
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($order_work) {
            $order_work->Код_Работы=OrderWork::max('Код_Работы')+1;            
        });

    }
}
