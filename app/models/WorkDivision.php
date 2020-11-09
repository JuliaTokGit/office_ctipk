<?php
use Illuminate\Database\Eloquent\Model;

class WorkDivision extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Работы_Отдел';
    protected $primaryKey = 'Код_Записи';
    public $incrementing = false;
    protected $title = 'Работы отдел';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Записи'];
    public $timestamps = false;
}
