boolselect = function ( data, type, full, meta )
{ return data==1?'Да':'Нет'; }

payments = function ( data, type, full, meta )
{return `<a href="/payments/paysheet_id/${data}" type="button" class="btn btn-primary">Payments</a>`}

state = function ( data, type, full, meta )
{return `<strong>${data.length?data[0].name:''}</strong>`}
// {return `<strong>${data[0].name}</strong> <a href="/payment_states/payment_id/${full.id}">all&nbsp;states</a>`}

paysheet = function ( data, type, full, meta )
{return `<a href="/paysheets/id/${data.id}">${data.uuid}</a>`}

columns=[
    { data: "id", "name":"id", visible:false },
    { data: "id", "name":"id", title:"id" },
    { data: "uuid", "name":"uuid", title:"uuid" },
    { data: "paysheet", "name":"id", title:"paysheet", defaultContent:"", render:paysheet },
    { data: "paysheet.account_id", "name":"account_id", title:"account", defaultContent:"" },
    { data: "amount", "name":"amount", title:"amount" , defaultContent:"" },
    { data: "paysheet.currency_code", "name":"currency_code", title:"currency" , defaultContent:"" },
    { data: "name", "name":"name", title:"name" , defaultContent:"" },
    { data: "phone", "name":"phone", title:"phone" , defaultContent:"" },
    { data: "states", "name":"id", title:"state", render:state },


    { data: "created_at", "name":"created_at", title:"created", defaultContent:"" },
    { data: "updated_at", "name":"updated_at", title:"updated", defaultContent:"" },
];
