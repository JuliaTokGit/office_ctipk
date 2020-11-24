<?php
use Illuminate\Database\Eloquent\Model;

class Issuer extends Model
{
    protected $connection='mssql';
    protected $table = 'Справочник_Кем_Выдан_Паспорт';
    protected $primaryKey = 'Код_Записи';    
    protected $title = 'Наименование';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Записи'];
    public $timestamps = false;
}
