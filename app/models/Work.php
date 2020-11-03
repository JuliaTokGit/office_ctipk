<?php
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Работ_Заявка';
    protected $primaryKey = 'Код_Работы';
    public $incrementing = false;
    protected $title = 'Справочник структурированных работ';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Работы'];
    public $timestamps = false;
    public $relations = ['work_group'];

    public function work_group()
    {
        return $this->belongsTo('Inventarisation','Код_Группы');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($work) {
            $work->Код_Работы=Work::max('Код_Работы')+1;            
        });

    }
}
