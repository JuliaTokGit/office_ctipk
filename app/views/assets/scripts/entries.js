boolselect = function ( data, type, full, meta )
{ return data==1?'Да':'Нет'; }

transaction = function ( data, type, full, meta )
{return `<a href="/transactions/id/${data}" type="button" class="btn btn-primary">Transaction # ${data}</a>`}

gl_account = function ( data, type, full, meta )
{return `${data.id} (${data.name})`}

columns=[
    { data: "id", "name":"id",  visible:false,  },
    { data: "id", "name":"id", title:"id" },
    { data: "account_id", "name":"account_id", title:"account", defaultContent:""  },
    { data: "gl_account.name", "name":"gl_account_id", title:"gl_account" , defaultContent:"", render:gl_account },
    { data: "type", "name":"type", title:"type" , defaultContent:"" },
    { data: "amount", "name":"amount", title:"amount" , defaultContent:"" },
    { data: "currency.name", "name":"currency_id", title:"currency" , defaultContent:"" },
    { data: "balance_before", "name":"balance_before", title:"balance_before" , defaultContent:"" },
    { data: "balance_after", "name":"balance_after", title:"balance_after" , defaultContent:"" },
    { data: "transaction_id", "name":"transaction_id", title:"transaction" , defaultContent:"", render:transaction },    
    { data: "created_at", "name":"created_at", title:"created", defaultContent:"" },
    { data: "updated_at", "name":"updated_at", title:"updated", defaultContent:"" },
];
