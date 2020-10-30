$(function(){
  $( ".select_load" ).each(function() {    
    $( this ).val($(this).data("value")).trigger("change");
  });
});
$('.switch_client').on('click', function(e) {
  $(".switched_forms").hide();
  $($(this).data("block")).show();
  $($(this).data("switch")).prop("checked",true);
});
