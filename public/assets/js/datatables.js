$(document).ready(function() {

  if (typeof(dt_options) !== 'undefined') {
    $.extend(true, $.fn.dataTable.defaults, dt_options);
  }

  $('.datatable').each(function(i) {
    if ($(this).data('dataurl') !== '') {
      var dataurl = $(this).data('dataurl');
    } else {
      var dataurl = window.dataurl;
    }

    initTable(this, dataurl, columns);
  });
});






function initTable(table, dataurl, columns) {
  var tbl = $(table).DataTable({
    'ajax': {
      "url": dataurl
    },
    columns: columns
  });
  tbl
    .on('init', function(e, dt) {
      window.filtered = tbl.rows().data();
    })
    .on('select', function(e, dt, type, indexes) {
      loadData(dt.rows(indexes).data().toArray()[0]);
      $('.btn-relative').prop('disabled', false);
    })
    .on('deselect', function(e, dt, type, indexes) {
      $('.btn-relative').prop('disabled', true);
    })
    .on('dblclick', 'tr', function(e, dt, type, indexes) {
      loadData(tbl.row(this).data());
      $('#modalEdit').modal('show');
    })
    .on('row-reorder', function(e, diff, edit) {

      $.ajax({
        method: "POST",
        url: dataurl,
        data: {
          action: "reorder",
          sequence: edit.values
        }
      });
    }).on('draw', function() {

      $(".ajax-form").unbind();
      $(".ajax-form").submit(ajaxForm);

    }).on('search.dt', function(e, settings) {
      window.filtered = tbl.rows({
        filter: 'applied'
      }).data();
    });

  $("#default_filter").detach().appendTo("#search_block");
  if (typeof(initExpanding) !== 'undefined') {
    initExpanding(table);
  }
}




function ajaxForm(event) {
  event.preventDefault();
  var form_data = new FormData(this);
  var form = this;
  var url = window.dataurl;
  if ($(this).attr('action') !== undefined) {
    url = $(this).attr('action');
  }
  if ($(this).data('dataurl')) {
    url = $(this).data('dataurl');
  }
  cro = $(this).find('.cropped');
  if (cro.length) {
    file_orig = cro.parent().find('.text').text();
    name_without_ext = file_orig.substr(0, file_orig.lastIndexOf('.')) || file_orig;
    filename = name_without_ext + '.png';
    form_data.append(cro.data('name'), cropper.getBlob(), filename);
  }

  $.ajax({
      method: "POST",
      url: url,
      data: form_data,
      context: this,
      processData: false,
      contentType: false,
      xhr: function() {
        var xhr = new window.XMLHttpRequest();
        $(form).find('button.progressed').hide();
        var prog = $(form).find('div.progressed');
        prog.show();
        xhr.upload.addEventListener("progress", function(evt) {
          if (evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total;
            percentComplete = parseInt(percentComplete * 100);
            prog.find('#percentage').text(percentComplete);
            prog.find('.progress-bar').css({
              width: percentComplete + '%'
            });
            if (percentComplete === 100) {
              $(form).find('button.progressed').show();
              prog.hide();
              // window[$(form).data('done')](data, $(form));
            }

          }
        }, false);
        return xhr;
      }
    })
    .done(function(data) {
      window[$(this).data('done')](data, $(this));
    });
}
