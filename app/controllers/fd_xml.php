<?php
/*
 * Библиотека для генерации UUID
 */
use Ramsey\Uuid\Uuid;
/*
 * Request модель таблицы request
 * Метод find - встроенный в Eloquent? Да
 * Где описан массив $filters[]?
 */
$request=Request::find($filters['request_id']);
/*
 * К массиву $request применяем метод valuations()[Замыкание?], или это генератор запросов,
 * с предопределенной областью действия?
 * который описан в Request.php и устанавливает соотношение один ко многим
 * Далее производим выборку где 'have_changed' не равно 'без изменений'
 * группируем по group_id
 * извлекаем значения по 'group_id' (столбцы или колонки?)
 * помещаем всё в архив
 */
$grouplist=$request->valuations()->where('have_changed','!=','без изменений')->groupBy('group_id')->pluck('group_id')->toArray();
/*
 *В столбец groups массива $context[] помещаем строки из модели таблицы Group, где присутствует 'id'. Зачем метод get()?
 * Почему не toArray?
 */
$context['groups']=Group::whereIn('id', $grouplist)->get();
/*
 *К массиву $request[] применяется метод valuations(), подготавливая генератор запросов,
 * Далее производим выборку где 'have_changed' не равно 'без изменений'
 * группируем по appraise
 * извлекаем значения по appraise
 * и помещаем в массив
 */
$e_groups=$request->valuations()->where('have_changed','!=','без изменений')->groupBy('appraise')->pluck('appraise')->toArray();
/*
 * Объявляем массив $evalution_groups[]
 */
$evaluation_groups=[];
/*
 *Тест, тест, тест
 */
foreach ($e_groups as $group) {
    $evaluation_groups[]=[
        'description'=>$group,
        'vals'=>$request->valuations()->where('have_changed','!=','без изменений')->where('appraise',$group)->get()
    ];
}
$context['request']=$request;
// $context['vals']=$request->valuations()->where('have_changed','!=','без изменений')->get();
$context['evaluation_groups']=$evaluation_groups;
$context['date']=date("Y-m-d");
/*
 * uuid4 не включает в себя метки времени и ПК
 */
$guid= Uuid::uuid4();
$context['guid']=$guid;
header('Content-Type: application/xml');
header('Content-Disposition: attachment;filename="FD_State_Cadastral_Valuation_'.$request->number.'.xml"');
header('Cache-Control: max-age=0');