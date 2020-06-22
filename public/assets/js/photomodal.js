$( function() {
    $.ajax({        
        method:"POST",
        url: all_photos_url,
        processData: false,
        contentType: false
    })
    .done(function(data){
        all_photos=JSON.parse(data);
        all_photos_ids=fill_by_photos(all_photos.data,'#all_photos');
    });

    $('#photos').sortable({
        connectWith: ".connectedSortable",
        update: function(event, ui) {
            $('#photos').find('.draggable').each(function(index){
                if($(this).data('id')!=$(ui.item).data('id')){
                    var element = $(this).detach();
                    $('#available_photos').append(element);
                }      
            });

            $('#photos-list').empty();

            var input=$('<input />').attr({
                    "type":'hidden',
                    "name":object_photos_property_id,
                    "value":$(ui.item).data('id')
            }).appendTo('#photos-list');
            
        }
    }).disableSelection();


    $('#available_photos').sortable({
        connectWith: ".connectedSortable"
    }).disableSelection();

  
    $('#photo').click(function(event){
        event.stopImmediatePropagation();
        load_photos($(this).val());
    });

    load_photos = function(id) {
        $('#available_photos').html($('#all_photos').html());
        if (all_photos_ids.length>7){$('#available_photos').height(280);}
        $.ajax({
            method:"POST",
            url: object_photos_url+id,
            processData: false,
            contentType: false
        })
        .done(function(data){
            $('#photos-list').empty();
            obj=JSON.parse(data);
            if(obj.data[0][object_photos_property]!==null){
                photo_id=fill_by_photo(obj.data[0][object_photos_property],'#photos');
                var input=$('<input />').attr({
                        "type":'hidden',
                        "name":object_photos_property_id,
                        "value":photo_id
                }).appendTo('#photos-list');
                $('#available_photos').find('#photo'+photo_id).remove();
            }
        })
    }

    uploaded = function(data, form){
        dat = jQuery.parseJSON(data);
        all_photos.data.push(dat);
        all_photos_ids=fill_by_photos(all_photos.data,'#all_photos');
        load_photos($('#photo').val());
        $('#tab_all_photo').click();
        form[0].reset();
        form.find(".btn-file").find('.text').text('Выберите файл для загрузки');
    }

    photolinked = function(data, form){
        $('#photos','#available_photos','#photos-list').empty();
        $('#modalPhoto').modal('hide');
    }

    fill_by_photo = function (photo,target){
        $(target).empty();
        var container=$('<div />').attr({
            'id': 'photo'+photo.id,
            'data-id':photo.id,
            'class':'draggable'
        }).appendTo(target);

        var link=$('<a/>').attr({
            'href':uploaded_url+photo.filename,
            'data-lightbox':target,
            'data-id':photo.id,
            'data-title':photo.description
        }).appendTo(container);
        
        var img = $('<img />').attr({
            'id':'image'+photo.id,
            'title':photo.description,
            'alt':photo.description,
            'src': uploaded_url+photo.filename+'?w=88&h=88&cf'
        }).appendTo(link);

        var description=$('<div/>').attr({
            'class':'description'
        }).html(photo.description).appendTo(container);
        
        return photo.id;
    }

    fill_by_photos = function (data,target){
        $(target).empty();
        var ids=new Array();
        $.each(data, function(key,photo){
            ids.push(photo.id);
            var container=$('<div />').attr({
                'id': 'photo'+photo.id,
                'data-id':photo.id,
                'class':'draggable'
            }).appendTo(target);
            
            var link=$('<a/>').attr({
                'href':uploaded_url+photo.filename,
                'data-lightbox':target,
                'data-id':photo.id,
                'data-title':photo.description
            }).appendTo(container);

            var img = $('<img />').attr({
                'id':'image'+photo.id,
                'title':photo.description,
                'alt':photo.description,
                'src': uploaded_url+photo.filename+'?w=88&h=88&cf'
            }).appendTo(link);

            var description=$('<div/>').attr({
                'class':'description'
            }).html(photo.description).appendTo(container);
        });
        return ids;
    }

    

$('.image-modal').click(function(){
    $('.imagepreview').attr('src', $(this).find('img').data('src'));
    $('#imagemodal').modal('show');
});
});