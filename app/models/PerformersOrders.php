<?php
use Illuminate\Database\Eloquent\Model;

class PerformersOrders extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Исполнители_Заявка';
    protected $primaryKey = 'Код_Исполнителя';
    protected $title = 'Исполнители по видам работ';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Исполнителя'];
    public $timestamps = false;
}
