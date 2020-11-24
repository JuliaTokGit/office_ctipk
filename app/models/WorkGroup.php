<?php
use Illuminate\Database\Eloquent\Model;

class WorkGroup extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Группы_Работ_Заявка';
    protected $primaryKey = 'Код_Записи';
    // public $incrementing = false;
    protected $title = 'Справочник групп структурированных работ';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Записи'];
    public $timestamps = false;
    public $relations = [];

    public function works()
    {
        return $this->hasMany('Work','Код_Группы');
    }
}
