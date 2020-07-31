boolselect = function ( data, type, full, meta )
{ return data==1?'Да':'Нет'; }

show_edit = function ( data, type, full, meta )
{return `<a href="/order/id/${data}" type="button" class="btn btn-primary">Редактировать</a><a href="/delete-order/id/${data}" type="button" class="btn btn-primary">Delete</a>`}

columns=[
    { data: "Код_Заявки", "name":"Код_Заявки",  visible:false,  },
    { data: "Код_Заявки", "name":"Код_Заявки", title:"Код Заявки" },
    
    { data: "Номер_Заявки", "name":"Номер_Заявки", title:"Номер Заявки", defaultContent:""  },
    { data: "Заказчик", "name":"Заказчик", title:"Заказчик", defaultContent:""  },    
    { data: "Работы", "name":"Работы", title:"Работы", defaultContent:""},
    { data: "Код_Заявки", "name":"Код_Заявки", title:"Код Заявки",render: show_edit },
];
