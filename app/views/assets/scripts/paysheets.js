boolselect = function ( data, type, full, meta )
{ return data==1?'Да':'Нет'; }

payments = function ( data, type, full, meta )
{return `<a href="/payments/paysheet_id/${data}" type="button" class="btn btn-primary">Payments</a>`}

status_render = function ( data, type, full, meta )
{return `<strong>${data.length?data[0].name:''}</strong>`}
// {return `${data[0].name} <a href="/paysheet_statuses/paysheet_id/${full.id}">Statuses</a>`}


columns=[
    { data: "id", "name":"id",  visible:false },
    { data: "id", "name":"id", title:"id" },
    { data: "uuid", "name":"uuid", title:"uuid" },
    { data: "account_id", "name":"account_id", title:"account", defaultContent:"" },
    { data: "total_amount", "name":"total_amount", title:"total amount" , defaultContent:"" },
    { data: "currency_code", "name":"currency_code", title:"currency" , defaultContent:"" },
    { data: "statuses", "name":"id", title:"status", defaultContent:"", render:status_render },
    { data: "id", "name":"id", title:"payments", defaultContent:"", render:payments },
    { data: "created_at", "name":"created_at", title:"created", defaultContent:"" },
    { data: "updated_at", "name":"updated_at", title:"updated", defaultContent:"" },
];
