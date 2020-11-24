<?php
use Illuminate\Database\Eloquent\Model;

class CtiUser extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Пользователи';
    protected $primaryKey = 'Код';
    protected $title = 'Таблица_Пользователи';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код'];
    public $timestamps = false;

}

