boolselect = function ( data, type, full, meta )
{ return data==1?'Да':'Нет'; }

columns=[
    { data: "id", "name":"id",  visible:false,  },
    { data: "id", "name":"id", title:"id" },
    { data: "name", "name":"name", title:"name", defaultContent:""  },
    { data: "description", "name":"description", title:"description" , defaultContent:"" },
    { data: "scheme", "name":"scheme", title:"scheme" , defaultContent:"" },
    { data: "template", "name":"template", title:"template" , defaultContent:"" },
    { data: "starting_status.name", "name":"starting_status_id", title:"starting status" , defaultContent:"" },
    { data: "created_at", "name":"created_at", title:"created", defaultContent:""   },
    { data: "updated_at", "name":"updated_at", title:"updated", defaultContent:""    },
];
