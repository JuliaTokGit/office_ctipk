<?php

foreach ($page->datatables as $dt) {
    $dt = json_decode(json_encode($dt), true);
    $crud = $dt['crud_modals'];
    if (isset($dt['id'])) {
        $suffix = $dt['id'];
    } else {
        $suffix = '';
    }
    $create = ['title' => $crud['titles']['create'], 'id' => 'modalCreate'.$suffix];
    $edit = ['title' => $crud['titles']['edit'], 'id' => 'modalEdit'.$suffix];
    $delete = ['title' => $crud['titles']['delete'], 'subheader' => $crud['subheaders']['delete'], 'id' => 'modalDelete'.$suffix];

    $create['body'] = [
        'data' => [['name' => 'done', 'value' => 'create'], ['name' => 'tableId', 'value' => '#'.$dt['tableId']]],
        'class' => 'ajax-form',
        'fields' => [['hidden' => 'action', 'value' => 'create']],
        'buttons' => [[
            'type' => 'submit',
            'text' => 'Создать',
            'class' => 'btn-complete',
        ]],
    ];

    $edit['body'] = [
        'data' => [['name' => 'done', 'value' => 'edit'], ['name' => 'tableId', 'value' => '#'.$dt['tableId']]],
        'class' => 'ajax-form',
        'fields' => [['hidden' => 'action', 'value' => 'edit']],
        'buttons' => [[
            'type' => 'submit',
            'text' => 'Сохранить',
            'class' => 'btn-primary',
        ]],
    ];

    $delete['body'] = [
        'data' => [['name' => 'done', 'value' => 'del'], ['name' => 'tableId', 'value' => '#'.$dt['tableId']]],
        'class' => 'ajax-form',
        'fields' => [['hidden' => 'action', 'value' => 'delete']],
        'buttons' => [[
            'type' => 'submit',
            'text' => 'Удалить',
            'class' => 'btn-danger',
        ]],
    ];

    $edit['body']['fields'][] = [
        'hidden' => 'id',
        'data' => ['name' => 'obj', 'value' => 'id'],
    ];

    $delete['body']['fields'][] = [
        'hidden' => 'id',
        'data' => ['name' => 'obj', 'value' => 'id'],
    ];

    foreach ($crud['fields'] as $field) {
        $field[$field['type']] = $field['property'];
        $create['body']['fields'][] = $field;
        $field['data'] = ['name' => 'obj', 'value' => $field['property']];
        $edit['body']['fields'][] = $field;
    }

    unset($dt->crud_modals);

    if (!isset($context['page']->properties->modal)) {
        $context['page']->properties->modal = [];
    }

    $context['page']->properties->modal[] = $create;
    $context['page']->properties->modal[] = $edit;
    $context['page']->properties->modal[] = $delete;
}
