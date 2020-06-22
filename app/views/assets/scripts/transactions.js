boolselect = function ( data, type, full, meta )
{ return data==1?'Success':'Fail'; }

gl_account = function ( data, type, full, meta )
{return `${data.id} (${data.name})`}

doc = function ( data, type, full, meta )
{return `<a href="/documents/id/${data}" type="button" class="btn btn-primary">Document # ${data} </a>`}

entries = function ( data, type, full, meta )
{return `<a href="/entries/transaction_id/${data}" type="button" class="btn btn-primary">Entries</a>`}


columns=[
    { data: "id", "name":"id",  visible:false,  },
    { data: "id", "name":"id", title:"id" },
    { data: "data", "name":"data", title:"data" , defaultContent:"" },
    { data: "headers", "name":"headers", title:"headers" , defaultContent:"" },
    { data: "idempotency_key", "name":"idempotency_key", title:"idempotency_key" , defaultContent:"" },
    { data: "success", "name":"success", title:"success" , defaultContent:"", render: boolselect },
    { data: "doc_id", "name":"doc_id", title:"doc_id" , defaultContent:"", render: doc },
    { data: "id", "name":"id", title:"entries" , defaultContent:"", render: entries },
    { data: "created_at", "name":"created_at", title:"created", defaultContent:"" },
    { data: "updated_at", "name":"updated_at", title:"updated", defaultContent:"" },
];
