boolselect = function ( data, type, full, meta )
{ return data==1?'Да':'Нет'; }

entries = function ( data, type, full, meta )
{return `<a href="/entries/account_id/${data}/gl_account_id/${full.gl_account_id}" type="button" class="btn btn-primary">Entries</a>`}

gl_account = function ( data, type, full, meta )
{return `${data.id} (${data.name})`}


columns=[
    { data: "id", "name":"id",  visible:false,  },
    { data: "id", "name":"id", title:"id" },
    { data: "account_id", "name":"account_id", title:"account", defaultContent:""  },
    { data: "gl_account", "name":"gl_account_id", title:"gl_account" , defaultContent:"" , render:gl_account},
    { data: "amount", "name":"amount", title:"amount" , defaultContent:"" },
    { data: "currency.name", "name":"currency_id", title:"currency" , defaultContent:"" },
    { data: "id", "name":"id", title:"entries" , defaultContent:"", render:entries },
    { data: "created_at", "name":"created_at", title:"created", defaultContent:"" },
    { data: "updated_at", "name":"updated_at", title:"updated", defaultContent:"" },
];
