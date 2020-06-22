boolselect = function ( data, type, full, meta )
{ return data==1?'Да':'Нет'; }

show_types = function ( data, type, full, meta )
{return `<a href="/document_types/group_id/${data}" type="button" class="btn btn-primary">Items</a>`}

act = function ( data, type, full, meta )
{return `<a href="/act/request_id/${data}" type="button" class="btn btn-primary">Акт</a>`}

xml = function ( data, type, full, meta )
{return `<a href="/xml/request_id/${data}" type="button" class="btn btn-primary">Xml</a>`}


columns=[
    { data: "id", "name":"id",  visible:false,  },
    { data: "id", "name":"id", title:"id" },
    { data: "request.name", "name":"name", title:"Запрос", defaultContent:""  },
    { data: "upload.filename", "name":"filename", title:"файл", defaultContent:""  },
    { data: "info", "name":"info", title:"info", defaultContent:"" },
    { data: "guid", "name":"guid", title:"guid", defaultContent:"" },
    { data: "quantity", "name":"quantity", title:"Количество", defaultContent:"" },
    { data: "created_at", "name":"created_at", title:"Создано", defaultContent:"" },
    { data: "updated_at", "name":"updated_at", title:"Обновлено", defaultContent:"" },
];
