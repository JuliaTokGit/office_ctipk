<?php
use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    protected $connection='mssql';
    protected $table = 'Справочник_Виды_Деятельности';
    protected $primaryKey = 'Код_Записи';
    protected $title = 'Наименование';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Записи'];
    public $timestamps = false;
}
