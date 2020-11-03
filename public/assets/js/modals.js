function loadData(data) {
  $.each(data, function(index, value) {
    target = $("[data-obj=" + index + "]");
    if ($.isArray(value)) {

      val = [];
      $.each(value, function(index, value) {
        val.push(value.id);
      });
      target.val(val).trigger("change");
    } else if ($.isPlainObject(value)){
      $.each(value, function(index_sub, value_sub) {
        target = $("[data-obj='" + index +"."+ index_sub +"']");
        loadElement(target,value_sub);
      });
    }
    else {
      loadElement(target,value);
    }
  });
}

function loadElement(target,value){
  target.val(value).trigger("change");

  if (target.hasClass("summernote")) {
    target.summernote('code', value);
  }
  if (target.is('span')) {
    target.html(value);
  }
  if (target.hasClass("btn-group")) {
    target.find("input[value='" + value + "']").attr('checked', 'checked');
  }
}

/// Далее кнопки и модалы для действий над данными


$('.btn-modal').click(function() {
  var modalname = $(this).data('modal');
  $(modalname).modal('show');
});



$('#reorder').click(function(event) {
  if ($(this).hasClass('btn-success')) {
    $(this).removeClass('btn-success');
    reorder_off($(this).data('tableid'));
  } else {
    $(this).addClass('btn-success');
    $('.btn-relative').prop('disabled', true);
    reorder_on($(this).data('tableid'));
  }
  // event.stopImmediatePropagation();
});

$('#download').click(function(event) {
  event.stopImmediatePropagation();
  file_download_link_to_clipboard($(this).data('tableid'));
});

$('#link').click(function(event) {
  event.stopImmediatePropagation();
  link_to_clipboard($(this).data('tableid'));
});


$('#duplicate').click(function(event) {
  event.stopImmediatePropagation();
  duplicate($(this).data('tableid'));
});

duplicate = function(tableId) {
  var dataurl = typeof(window.dataurl) !== 'undefined' ? window.dataurl : '';
  if ($(tableId).data('dataurl') !== '') {
    var dataurl = $(tableId).data('dataurl');
  }
  dat = $(tableId).DataTable().rows({
    selected: true
  }).data()[0];
  $.ajax({
      method: "POST",
      url: dataurl,
      data: {
        action: "duplicate",
        id: dat.id
      }
    })
    .done(function(data) {
      $(tableId).DataTable({
        'ajax': {
          "url": dataurl
        },
        columns: columns,
        "order": [
          [0, "desc"]
        ],
        rowReorder: false
      });
    });
}

add_to_campaign = function(data, form) {
  dat = jQuery.parseJSON(data);
  var tableId = form.data('tableid');
  var row = $(tableId).find('#' + dat.id);
  row.find('.remove-spot').removeClass('hidden');
  row.find('.add-spot').addClass('hidden');
}

remove_from_campaign = function(data, form) {
  dat = jQuery.parseJSON(data);
  var tableId = form.data('tableid');
  var row = $(tableId).find('#' + dat.id);
  row.find('.remove-spot').addClass('hidden');
  row.find('.add-spot').removeClass('hidden');
}

remove_row = function(data, form) {
  dat = jQuery.parseJSON(data);
  var tableId = form.data('tableid');
  // console.log(dat);
  $(tableId).DataTable().row('#' + dat.id).remove().clearPipeline().draw(false);
}


create = function(data, form) {
  data = jQuery.parseJSON(data);
  // console.log(data);
  var tableId = form.data('tableid');
  if ($.isArray(data)) {
    $.each(data, function(index, dat) {
      var row = $(tableId).DataTable().row.add(dat);
      row.clearPipeline().draw(false);
    });
  } else {
    dat = data;
    var row = $(tableId).DataTable().row.add(dat);
    row.clearPipeline().draw(false);
  }
  if (typeof childFormat !== 'undefined') {
    row.child(childFormat(row.data()), 'rowchild bg-master-lighter').show();
  }
  $('#modalCreate').modal('hide');
  form[0].reset();
  $('.imageBox, .imageBox_controls').hide();
  form.find(".imageBox").css('background-image', 'none');
  form.find(".btn-file").find('#file').removeClass('cropped');
  form.find(".btn-file").find('.text').text('Выберите файл для загрузки');
  form.find(".select2").val("").trigger("change");
  form.find('.summernote').each(function() {
    $(this).summernote('code', '');
  });
}


edit = function(data, form) {  
  dat = jQuery.parseJSON(data);
  var tableId = form.data('tableid');
  var row = $(tableId).DataTable().row('#' + dat.id);
  // console.log(dat);
  // row.data(dat).draw(false);
  $(tableId).dataTable().fnUpdate(dat,row,undefined,false);
  if (row.child() !== undefined) {
    row.child(childFormat(row.data()), 'rowchild bg-master-lighter').show();
  }
  $('.imageBox, .imageBox_controls').hide();
  form.find(".btn-file").find('.text').text('Выберите файл для загрузки');
  $('#modalEdit').modal('hide');
}


del = function(data, form) {
  var tableId = form.data('tableid');
  $(tableId).DataTable().row('.selected').remove().clearPipeline().draw(false);
  $(form.data('modal')).modal('hide');
  $('#modalDelete').modal('hide');
}

reorder_on = function(tableId) {
  var dataurl = typeof(window.dataurl) !== 'undefined' ? window.dataurl : '';
  if ($(tableId).data('dataurl') !== '') {
    var dataurl = $(tableId).data('dataurl');
  }

  $(tableId).DataTable({
    'ajax': {
      "url": dataurl
    },
    "order": [
      [0, "desc"]
    ],
    columns: columns,
    bPaginate: false,
    searching: false,
    rowReorder: {
      dataSrc: "sequence",
      selector: 'tr'
    }
  });


}


reorder_off = function(tableId) {
  var dataurl = typeof(window.dataurl) !== 'undefined' ? window.dataurl : '';
  if ($(tableId).data('dataurl') !== '') {
    var dataurl = $(tableId).data('dataurl');
  }
  initTable(tableId, dataurl, columns);
}


file_download_link_to_clipboard = function(tableId) {
  dat = $(tableId).DataTable().rows({
    selected: true
  }).data()[0]
  // dat = jQuery.parseJSON(data);
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val('<a href="/download/id/' + dat.id + '"><i class="' + dat.type.icon + '"></i> ' + dat.description + '</a>').select();
  document.execCommand("copy");
  $temp.remove();
  alert('Скопировано в буфер обмена');
}


link_to_clipboard = function(tableId) {
  dat = $(tableId).DataTable().rows({
    selected: true
  }).data()[0]
  // dat = jQuery.parseJSON(data);
  var $temp = $("<input>");
  $("body").append($temp);
  var path = uploaded_url;
  if (dat.subdir != null) {
    path = path + dat.subdir + '/';
  };
  path = path + encodeURIComponent(dat.filename);
  $temp.val(path).select();
  document.execCommand("copy");
  $temp.remove();
  alert('Скопировано в буфер обмена');
}
