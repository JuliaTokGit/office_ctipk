short_str = function ( data, type, full, meta ){
    return data.substr( 0, 255 );
}

federal = function ( data, type, full, meta ){
    return data?'Федеральная кампания':'Региональная кампания';
}

spots = function ( data, type, full, meta ){
    output='';
    $.each(data, function(key, spot){

        output=output+spot.name+'<br>'
    })
    return output;
}

fullname = function ( data, type, full, meta ){
    return data?data.firstname+' '+data.lastname:'';
}

placements = function ( data, type, full, meta ){
    if (data.length>0){
        var out='';
        $.each(data, function(key,plmt){
            out=out+plmt.campaign.name+'<br>(<strong>'+plmt.status.name+'</strong>)<br>';
        });
        return out;
    }else{
        return 'Нет размещений';
    }
}

pic = function ( data, type, full, meta ){
    return data?`<a href="/assets/img/${data.filename?data.filename:full.filename}" data-lightbox="photo${data.id?data.id:full.id}" data-id="${data.id?data.id:full.id}" data-title=""><img src="/assets/img/${data.filename?data.filename:full.filename}?w=150&h=150&cf" style="height:100px;"/>`:'';
}

photos_with_uploader = function ( data, type, full, meta ){
    uploader=`
        <form method="POST" enctype="multipart/form-data" action="/data/placements/" class="ajax-form" data-done="updateSpot" data-tableid="#default" style="display:inline-block;">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="${data[0].id}"/>
        <input type="hidden" name="status_id" value="4"/>
        <input type="hidden" name="campaignstatus" value="4"/>
        <input class="uplo" type="file" name="uploads" accept="image/*" style="display:none;"/>
        <button title="Загрузить фото" class="upload-btn btn btn-sm" style="padding:2px 8px; font-size:20px;width:30px;height:30px;">+</button>
        </form>
        `;
    if (data[0].photos.length){
        ph='<div style="width:110px;">';
        $.each(data[0].photos, function(key, photo){
            console.log(photo);
            ph+=`<a href="/assets/img/${photo.filename?photo.filename:full.filename}" data-lightbox="photo${photo.id?photo.id:full.id}" data-id="${photo.id?photo.id:full.id}" data-title="Загружено ${photo.created_at}"><img src="/assets/img/${photo.filename?photo.filename:full.filename}?w=30&h=30&cf" class="b-thick b-grey m-r-5" style="height:30px; display:inline-block;"/></a>`
        });
        if (data[0].photos.length<3){ph+=uploader;}
        ph+='</div>';
    }else{
        ph='<div>'+uploader+'</div>';
    }
    return ph;
}

shop = function ( data, type, full, meta ){
    if (data!=null){
        return `<a href="/shop/id/${data.id?data.id:full.id}">${data.name?data.name:full.name}</a>`
    }else{
        return 'Магазин не задан';
    }
}

campaign = function ( data, type, full, meta ){
    return `<a href="/campaign/id/${data.id?data.id:full.id}">${data.name?data.name:full.name}</a>`
}

prices = function ( data, type, full, meta ){
    if (data.length>0){
        output='';
        $.each(data, function(key, price){
            output=output+price.name+' : '+price.rate+'<br>'
        })
    }else{
        output='Нет цен';
    }
    output=output+' &nbsp;&nbsp;&nbsp;<a class="btn btn-xs" href="/prices/spot_type_id/'+full.id+'">Редактировать цены</a>'
    return output;
}

price = function ( data, type, full, meta ){

    if (data && full.shop){
        return data*full.shop.category.price_multiplier*full.shop.city.type.price_multiplier+' ('+data+')';
    }else{
        return 'Цена не задана';
    }
}

color_status = function ( data, type, full, meta ) {
    return `<span class="label ${data.class}">${data.name}</span>`;
}

is_complete_status = function ( data, type, full, meta ) {
    return `<span class="label ${data?'label-inverse':''}">${data?'Установка завершена':'Комплект не устанавливался'}</span>`;
}

has_contract_status = function ( data, type, full, meta ) {
    return `<span class="label ${data?'label-success':''}">${data?'Заключен договор':'Договор не заключен'}</span>`;
}

store = function ( data, type, full, meta ){
    if (data!=null){
        return data.name
        // return `<a href="/store/id/${data.id?data.id:full.id}">${data.name?data.name:full.name}</a>`
    }else{
        return 'Салон не задан';
    }
}
