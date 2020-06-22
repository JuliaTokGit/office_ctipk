go_page = function ( data, type, full, meta )
{return `<a href="/conf/collection/tests/item/${data}" type="button" class="btn btn-primary">Подробнее</a>`}

import_page = function ( data, type, full, meta )
{return `<a href="/tests_sync/course_id/${data}" type="button" class="btn">Импортировать вопросы и ответы из конфига</a>`}

columns=[
    { data: "_id", "name":"_id", title:"Имя" },
    { data: "course.name", "name":"course.name", title:"Курс", "defaultContent": ""},
    { data: "_id", "name":"_id", title:"", render:go_page },
    { data: "_id", "name":"_id", title:"", render:import_page }
];
