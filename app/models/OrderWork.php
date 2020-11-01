<?php
use Illuminate\Database\Eloquent\Model;

class OrderWork extends Model
{
    protected $connection='mssql';
    protected $table = 'Таблица_Работы_Заявки';
    protected $primaryKey = 'Код_Работы';
    protected $title = 'Таблица работы заявки';
    protected $hidden = ['upsize_ts'];
    protected $guarded = ['Код_Работы'];
    public $timestamps = false;
    public $relations = ['inventarisation'];

    public function inventarisation()
    {
        return $this->belongsTo('Inventarisation','КО');
    }
}
