boolselect = function ( data, type, full, meta )
{ return data==1?'Да':'Нет'; }

show_edit = function ( data, type, full, meta )
{return `<a href="/form/id/${data}" type="button" class="btn btn-primary">Редактировать в билдере</a>`}

columns=[
    { data: "id", "name":"id",  visible:false,  },
    { data: "id", "name":"id", title:"id" },
    { data: "name", "name":"name", title:"название", defaultContent:""  },
    { data: "data_url", "name":"data_url", title:"путь", defaultContent:""  },
    { data: "data", "name":"data", title:"данные", defaultContent:""  },
    { data: "id", "name":"id", title:"действие", defaultContent:"", render:show_edit  },
    { data: "created_at", "name":"created_at", title:"created", defaultContent:"" },
    { data: "updated_at", "name":"updated_at", title:"updated", defaultContent:"" },
];
