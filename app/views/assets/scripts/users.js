is_active= function ( data, type, full, meta )
{return (data!=0)?'Да':'Нет'}


columns=[
    { data: "sequence", name:"sequence", visible:false},
    { data: "lastname", name:"lastname", title:"Фамилия" },
    { data: "firstname", name:"firstname", title:"Имя" },
    { data: "username", name:"username", title:"Email" },
    { data: "description", name:"description", title:"Описание" },
    { data: "upload", name:"upload", title:"Фото", render:pic},
    { data: "type.name", name:"user_type_id", title:"Тип", defaultContent:""   },
    { data: "active", name:"active", title:"Активен", render:is_active }
];

$(function(){
    $(".phone").inputmask({"mask":"+7(999) 999-9999"});
});
