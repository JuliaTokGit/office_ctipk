boolselect = function ( data, type, full, meta )
{ return data==1?'Да':'Нет'; }

show_types = function ( data, type, full, meta )
{return `<a href="/document_types/group_id/${data}" type="button" class="btn btn-primary">Items</a>`}

act = function ( data, type, full, meta )
{return `<div class="btn-group"><a href="/act/request_id/${data}" type="button" class="btn btn-primary">Акт</a><a href="/letter/request_id/${data}" type="button" class="btn btn-primary">Письмо</a></div>`}

xml = function ( data, type, full, meta )
{return `<div class="btn-group"><a href="/cost_xml/request_id/${data}" type="button" class="btn btn-primary">Cost XML</a><a href="/fd_xml/request_id/${data}" type="button" class="btn btn-primary">Fd XML</a><a href="/interact_xml/request_id/${data}" type="button" class="btn btn-primary">Interact XML</a></div>`}


columns=[
    { data: "id", "name":"id",  visible:false,  },
    { data: "id", "name":"id", title:"id" },
    { data: "name", "name":"name", title:"Источник", defaultContent:""  },
    { data: "proportion", "name":"proportion", title:"Количество", defaultContent:"" },
    { data: "id", "name":"id", title:"Акт", defaultContent:"", render: act },
    { data: "id", "name":"id", title:"XML", defaultContent:"", render: xml },
    { data: "requested_at", "name":"requested_at", title:"Запрошено", defaultContent:"" },
    { data: "created_at", "name":"created_at", title:"Создано", defaultContent:"" },
    { data: "updated_at", "name":"updated_at", title:"Обновлено", defaultContent:"" },
];
