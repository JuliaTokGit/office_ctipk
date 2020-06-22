go_page = function ( data, type, full, meta ) 
{return `<a href="/page/id/${data}" type="button" class="btn btn-primary">Подробнее</a>`}

columns=[
    { data: "_id", "name":"_id", title:"Имя" },
    { data: "title", "name":"title", title:"Название", "defaultContent": ""},
    { data: "_id", "name":"_id", title:"", render:go_page }
];