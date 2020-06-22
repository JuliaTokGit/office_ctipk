$.fn.modal.Constructor.prototype.enforceFocus = function () {}; //Фикс для нормальной работы select2 в модале

$.extend( true, $.fn.dataTable.defaults, {
  language:{
    processing: "Please wait...",
    search: "Search:",
    lengthMenu: "Show _MENU_ records",
    info: "Записи с _START_ до _END_ из _TOTAL_ records",
    infoEmpty: "Record from 0 to 0 of 0 records",
    infoFiltered: "(Filtered из _MAX_ records)",
    infoPostFix: "",
    loadingRecords: "Loading...",
    zeroRecords: "Empty data.",
    emptyTable: "Empty data.",
    paginate: {
      first: "First",
      previous: "Previous",
      next: "Next",
      last: "Last"
    },
    aria: {
      "sortAscending": ": активировать для сортировки столбца по возрастанию",
      "sortDescending": ": активировать для сортировки столбца по убыванию"
    },
    select: { rows: { _: "", 0: "", 1: "" } }
  },


  dom: "<'table-responsive'tf><'row'<p i>>",
  sPaginationType: "bootstrap",
  // pagingType: "full_numbers",
  select:{   style: 'single'   },
  destroy: true,
  searching: true,
  deferRender : true,
  autoWidth:false,
  order: [[ 0, "desc" ]],
  scrollCollapse: true,
  rowId: 'id',
  iDisplayLength: 10,

  fnDrawCallback: function(oSettings) {
      $(oSettings.nTableWrapper).find('.dataTables_info').parent().css({'min-height':'24px'});
      if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
          $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
          $(oSettings.nTableWrapper).find('.dataTables_info').hide();
      }else{
          $(oSettings.nTableWrapper).find('.dataTables_paginate').show();
          $(oSettings.nTableWrapper).find('.dataTables_info').show();
      }
  }

});
