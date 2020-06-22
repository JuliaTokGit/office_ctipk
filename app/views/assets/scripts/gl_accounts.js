boolselect = function ( data, type, full, meta )
{ return data==1?'Да':'Нет'; }

entries = function ( data, type, full, meta )
{return `<a href="/entries/gl_account_id/${data}" type="button" class="btn btn-primary">Entries</a>`}

balances = function ( data, type, full, meta )
{return `<a href="/balances/gl_account_id/${data}" type="button" class="btn btn-primary">Balances</a>`}

columns=[
    { data: "id", "name":"id",  visible:false,  },
    { data: "id", "name":"id", title:"id" },
    { data: "name", "name":"name", title:"name", defaultContent:""  },
    { data: "type", "name":"type", title:"type", defaultContent:""  },
    { data: "description", "name":"description", title:"description", defaultContent:""  },
    { data: "currency.name", "name":"currency_id", title:"currency" , defaultContent:"" },    
    { data: "decreasing_on", "name":"decreasing_on", title:"decreasing_on", defaultContent:""  },
    { data: "only_positive", "name":"only_positive", title:"only_positive", defaultContent:"" , render:boolselect  },
    { data: "id", "name":"id", title:"entries" , defaultContent:"", render:entries },
    { data: "id", "name":"id", title:"entries" , defaultContent:"", render:balances },
    { data: "created_at", "name":"created_at", title:"created", defaultContent:"" },
    { data: "updated_at", "name":"updated_at", title:"updated", defaultContent:"" },
];
