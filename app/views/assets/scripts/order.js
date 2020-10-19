$(function(){
  $( ".select_load" ).each(function() {    
    $( this ).val($(this).data("value")).trigger("change");
  });
});
$('input[name="exampleRadios"]').on('click', function(e) {
$(".switched_forms").hide();
$($(this).val()).show();

});
