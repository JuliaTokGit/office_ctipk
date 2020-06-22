activate= function ( data, type, full, meta ) 
{return `<a href="/activate-users/user_id/${data}" type="button" class="btn btn-primary">Активировать</a>`}


columns=[
    { data: "sequence", name:"sequence", visible:false},
    { data: "id", title:"Активация", render:activate },
    { data: "lastname", name:"lastname", title:"Фамилия" },
    { data: "firstname", name:"firstname", title:"Имя" },
    { data: "username", name:"username", title:"Email" },
    { data: "type.name", name:"user_type_id", title:"Тип"  }
];