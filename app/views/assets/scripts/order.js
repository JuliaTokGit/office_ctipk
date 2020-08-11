$(function(){
  $( ".select_load" ).each(function() {    
    $( this ).val($(this).data("value")).trigger("change");
  });
})