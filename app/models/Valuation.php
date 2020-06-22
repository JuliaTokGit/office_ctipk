<?php
use Jenssegers\Mongodb\Eloquent\Model;
use Carbon\Carbon;

class Valuation extends Model
{
    protected $title = 'Оценки объектов недвижимости';
    protected $connection = 'mongodb';
    protected $guarded = ['id'];
    protected $appends=['id'];
    
    protected $descriptions=['Кадастровый номер',
    'Кадастровый квартал',
    'ВРИ 1',
    'ВРИ 2',
    'ВРИ по документу',
    'Код расчета вида использования',
    'Примечание',
    'Площадь',
    'Дата',
    'Предыдущий КН',
    'КН ОКСа',
    'старый УПКС',
    'Код КЛАДР НП',
    'Код КЛАДР МР/ГО',
    'Район',
    'Type',
    'Город',
    'Type6',
    'Улица',
    'Type8',
    'Type9',
    'Дом',
    'НП',
    'Type11',
    'Иное',
    'Местоположение',
    'НП по коду КЛАДР из XML',
    'Сцепка НП и улицы',
    'Код КЛАДР Улицы',
    'Сцепка НП улицы дом',
    'Изменения',
    'Тип расчета',
    'Статус НП',
    'Зона в Перми для ИЖС и садов',
    'Зона в Перми для коммерции и пром',
    'Расстояние до основных дорог города, м',
    'Расположение земельного участка до административного центра НП  (м)',
    'Расположение земельного участка относительно первой линии основных дорог НП',
    'Расположение земельного участка относительно первой линии основных дорог НП (да нет)',
    'Расположение земельного участка до остановки  (м)',
    'Расстояние до подрабатываемых территорий (м)',
    'Расстояние до водного объекта (м)',
    'Водоснабжение по КН ЗУ',
    'Канализация по КН ЗУ',
    'Теплоснабжение по КН ЗУ',
    'Газоснабжение по КН ЗУ',
    'Водоснабжение по адресу',
    'Канализация по адресу',
    'Теплоснабжение по адресу',
    'Газоснабжение по адресу',
    'Количество ОН по адресу',
    'Учет коммуникаций',
    'Учет охранных зон',
    'Учет санитарно-защитных зон',
    'Площадь ограничений, накладываемых ОЗ на ОО, кв.м.',
    'Площадь ограничений, накладываемых СЗЗ на ОО, кв.м.',
    'Расположение земельного участка вблизи береговой линии (в ручную)',
    'Код и КК где присутствует подтопление для земельного участка (в ручную)',
    'Подтопление для земельного участка (да нет)',
    'КС',
    'УПКС',
    'Старые ЗУ',
    'Вновь образованный ОН',
    'Количество ОН ВО',
    'Описание оценочной группы (Description)',
    'Модель оценки кадастровой стоимости',
    'Дубли',
    'Изменение по объекту',
    'Наменование рсчетного файла',
    'Средний УПКС по 6 сегменту, руб./кв.м.',
    'ID_Group'];

    public function setDataAttribute($values)
    {
        $parcel=Parcel::whereCadastralNumber($values[0])->orderBy('created_at','DESC')->first();        
        $this->category=$values[2];
        $this->utilization=$values[4];
        $this->have_changed=$values[30];
        $this->type=$parcel->data['ObjectType'];
        $this->address=$values[25];
        $this->specific_cadastral_cost=str_replace(',','.',str_replace(' ', '', $values[60]));
        $this->cadastral_cost=str_replace(',','.',str_replace(' ', '', $values[59]));        
        $this->usage=$values[5];
        $this->area=$values[7];
        $this->method=$values[65];
        $this->appraise=$values[64];
        $this->determination_date=Carbon::parse($values[8])->toDateString();
        $this->group_id=$values[70];
        
        $this->attributes['data']=$values;

    }

    public function result()
    {
        return $this->belongsTo('Result');
    }

    public function request()
    {
        return $this->belongsTo('Request');
    }

    public function estate()
    {
        return $this->belongsTo('Estate');
    }

    public function group()
    {
        return $this->belongsTo('Group');
    }
}
