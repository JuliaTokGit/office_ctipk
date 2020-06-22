boolselect = function ( data, type, full, meta )
{ return data==1?'Да':'Нет'; }

entries = function ( data, type, full, meta )
{return `<a href="/entries/currency_id/${data}" type="button" class="btn btn-primary">Entries</a>`}

balances = function ( data, type, full, meta )
{return `<a href="/balances/currency_id/${data}" type="button" class="btn btn-primary">Balances</a>`}


columns=[
    { data: "id", "name":"id",  visible:false,  },
    { data: "code", "name":"code", title:"code" },
    { data: "name", "name":"name", title:"name", defaultContent:""  },
    { data: "symbol", "name":"symbol", title:"symbol" , defaultContent:"" },
    { data: "id", "name":"id", title:"entries" , defaultContent:"", render:entries },
    { data: "id", "name":"id", title:"balances" , defaultContent:"", render:balances },
    { data: "created_at", "name":"created_at", title:"created", defaultContent:"" },
    { data: "updated_at", "name":"updated_at", title:"updated", defaultContent:"" },
];
