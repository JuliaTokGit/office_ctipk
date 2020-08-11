<?php

// Проверки необходимых параметров
if (!isset($page->filters_bulk[0][0])) {
    die('Did not recieve name');
}
$name = $page->filters_bulk[0][0];
if (!isset($page->objects->{$name})) {
    die('Unknown name');
}

//Инициализация формата
header('Content-type:application/json');
$result = (object) ['results' => []];
if (isset($page->filters_bulk[0][1]) && $page->filters_bulk[0][1] == 'filter') {
    $result->results[] = (object) ['id' => '', 'text' => 'All'];
}

foreach ($filters as &$filter) {
    $filter=urldecode($filter);
}

//Подгружаем параметры из конфига
$object = $page->objects->{$name};

if (!isset($object->denied) || !in_array($user->type->str_id, $object->denied)) {
    // $name=$object->name;
    $class = new $object->name();
    $selected = $filters['id'] ?? null;
    $id = $object->id ?? 'id';
    $text = $object->text ?? 'name';
}

//получаем данные и заполняем структуру
if (isset($class)) {
    if (isset($filters['user_type_id'])) {
        $items = $class->where('user_type_id', $filters['user_type_id'])->get();
    } elseif (isset($filters['text'])) {
        // print_r($class->where($text, urldecode($filters['text']))->first());
        // die();
        $selected = $class->where($text, $filters['text'])->first()->{$id};
        // print_r($selected);
        // die();
        $items = $class->all();
    } elseif (isset($filters['active'])){
        $items = $class->where('active', $filters['active'])->get();
    } else {
        $items = $class->all();
    }

    if (isset($object->children)) {
        foreach ($items as $item) {
            $childs = [];
            $ch = $item->{$object->children}()->get();
            addItems($ch, $childs);
            $result->results[] = (object) ['children' => $childs, 'text' => $item->{$text}];
        }
    } else {
        addItems($items, $result->results);
    }
}

echo json_encode($result);

//Функция для добавления данных в массив объектов
function addItems($items, &$list)
{
    global $id, $text, $selected;
    foreach ($items as $item) {        
        if (isset($selected) && $selected == $item->{$id}) {            
            $list[] = (object) ['id' => $item->{$id}, 'text' => $item->{$text}, 'selected' => true];
        } else {
            $list[] = (object) ['id' => $item->{$id}, 'text' => $item->{$text}];
        }
    }

    return false;
}
