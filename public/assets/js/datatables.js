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
    "processing": true,
    "serverSide": true,
    "ajax": $.fn.dataTable.pipeline( {
        url: dataurl,
        pages: 3 // number of pages to cache
    } ),
    // 'ajax': {
    //   "url": dataurl
    // },
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
      $("button[data-modal='#modalEdit']").click();
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



//
// Pipelining function for DataTables. To be used to the `ajax` option of DataTables
//
$.fn.dataTable.pipeline = function ( opts ) {
  // Configuration options
  var conf = $.extend( {
      pages: 5,     // number of pages to cache
      url: '',      // script url
      data: null,   // function or object with parameters to send to the server
                    // matching how `ajax.data` works in DataTables
      method: 'GET' // Ajax HTTP method
  }, opts );

  // Private variables for storing the cache
  var cacheLower = -1;
  var cacheUpper = null;
  var cacheLastRequest = null;
  var cacheLastJson = null;

  return function ( request, drawCallback, settings ) {
      var ajax          = false;
      var requestStart  = request.start;
      var drawStart     = request.start;
      var requestLength = request.length;
      var requestEnd    = requestStart + requestLength;
       
      if ( settings.clearCache ) {
          // API requested that the cache be cleared
          ajax = true;
          settings.clearCache = false;
      }
      else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
          // outside cached data - need to make a request
          ajax = true;
      }
      else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
      ) {
          // properties changed (ordering, columns, searching)
          ajax = true;
      }
       
      // Store the request for checking next time around
      cacheLastRequest = $.extend( true, {}, request );

      if ( ajax ) {
          // Need data from the server
          if ( requestStart < cacheLower ) {
              requestStart = requestStart - (requestLength*(conf.pages-1));

              if ( requestStart < 0 ) {
                  requestStart = 0;
              }
          }
           
          cacheLower = requestStart;
          cacheUpper = requestStart + (requestLength * conf.pages);

          request.start = requestStart;
          request.length = requestLength*conf.pages;

          // Provide the same `data` options as DataTables.
          if ( typeof conf.data === 'function' ) {
              // As a function it is executed with the data object as an arg
              // for manipulation. If an object is returned, it is used as the
              // data object to submit
              var d = conf.data( request );
              if ( d ) {
                  $.extend( request, d );
              }
          }
          else if ( $.isPlainObject( conf.data ) ) {
              // As an object, the data given extends the default
              $.extend( request, conf.data );
          }

          return $.ajax( {
              "type":     conf.method,
              "url":      conf.url,
              "data":     request,
              "dataType": "json",
              "cache":    false,
              "success":  function ( json ) {
                  cacheLastJson = $.extend(true, {}, json);

                  if ( cacheLower != drawStart ) {
                      json.data.splice( 0, drawStart-cacheLower );
                  }
                  if ( requestLength >= -1 ) {
                      json.data.splice( requestLength, json.data.length );
                  }
                   
                  drawCallback( json );
              }
          } );
      }
      else {
          json = $.extend( true, {}, cacheLastJson );
          json.draw = request.draw; // Update the echo for each response
          json.data.splice( 0, requestStart-cacheLower );
          json.data.splice( requestLength, json.data.length );

          drawCallback(json);
      }
  }
};

// Register an API method that will empty the pipelined data, forcing an Ajax
// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
$.fn.dataTable.Api.register( 'clearPipeline()', function () {
  return this.iterator( 'table', function ( settings ) {
      settings.clearCache = true;
  } );
} );