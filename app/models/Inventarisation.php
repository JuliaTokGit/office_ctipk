<?php
use Illuminate\Database\Eloquent\Model;

class Inventarisation extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Виды_Инвентаризации';
    protected $primaryKey = 'Код_Вида';    
    protected $title = 'Наименование';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Вида'];
    public $timestamps = false;

    public function order_works()
    {
        return $this->hasMany('OrderWork');
    }
}
