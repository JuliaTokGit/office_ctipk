columns=[
    { data: "id", "name":"id",  title:"id"},
    { data: "filename", "name":"filename", title:"Имя" },
    { data: "description", "name":"description", title:"Описание" },
    { data: "content_type", "name":"content_type", title:"Тип" },
    { data: "file_size", "name":"file_size", title:"Размер" },
    { data: "created_at", "name":"created_at", title:"Загружен" },
    { data: "user", "name":"user_id", title:"Пользователь", render: fullname },
    { data: "type.icon", "name":"icon", visible:false, defaultContent:"" }
	];
