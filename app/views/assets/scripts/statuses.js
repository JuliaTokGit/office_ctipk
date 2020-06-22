boolselect = function ( data, type, full, meta )
{ return data==1?'Да':'Нет'; }

columns=[
    { data: "id", "name":"id",  visible:false,  },
    { data: "id", "name":"id", title:"id" },
    { data: "name", "name":"name", title:"name", defaultContent:""  },
    { data: "sequence", "name":"sequence", title:"sequence" , defaultContent:"" },
    { data: "created_at", "name":"created_at", title:"created", defaultContent:"" },
    { data: "updated_at", "name":"updated_at", title:"updated", defaultContent:"" },
];
