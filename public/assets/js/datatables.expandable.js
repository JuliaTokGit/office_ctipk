initExpanding=function(table){
   $(table).each(function(i){
        $(this).on('click','td.details-control', function (event) {
        // $(".details-control").click(function(event){
            event.stopImmediatePropagation();
            var tr = $(this).closest('tr');
            var row = $(table).DataTable().row( tr );



            if ( row.child.isShown() ) {
                row.child.hide();
                tr.find('td').removeClass('bg-master-lighter');
                $(this).find('i').removeClass('pg-arrow_minimize');
                $(this).find('i').addClass('pg-arrow_right');
            }
            else {
                tr.find('td').addClass('bg-master-lighter');
                row.child( childFormat(row.data()),'rowchild bg-master-lighter').show();
                $(this).find('i').removeClass('pg-arrow_right');
                $(this).find('i').addClass('pg-arrow_minimize');

                if (typeof initRanges !=='undefined') {
                    initRanges(row.data());
                }
            }
        }); 
    }); 
}

