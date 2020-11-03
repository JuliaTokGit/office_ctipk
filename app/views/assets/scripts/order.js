$(function(){
  $( ".select_load" ).each(function() {    
    $( this ).val($(this).data("value")).trigger("change");
  });
  if ($(".bordered").length){
    $([document.documentElement, document.body]).animate({
            scrollTop:  $(".bordered").offset().top-50
        }, 1000);
  }

  $('.switch_client:checked').click();

});

$('.switch_client').on('click', function(e) {
  $(".switched_forms").hide();
  $($(this).data("block")).show();
  $($(this).data("switch")).prop("checked",true);
});

$('.work-groups').on('select2:select', function(e) {
  url="/select-ajax/works//work_group_id/"+this.value;
  console.log(url);
  current=$(".work");
  $.ajax({
    type: 'GET',
    url:url 
}).then(function (data) {
   
console.log(data);
$(".works").html("");
    $('.works').select2("destroy").select2({                
       data: data.results,
       minimumResultsForSearch: (current.attr('data-disable-search') == 'true' ? -1 : 1),
       tags: current.attr('data-tags') == 'true',
   });
   $('.works').select2().trigger('change');
  });
  $('.works').prop('disabled',false);

});
boolselect = function ( data, type, full, meta )
{ return data==1?'Да':'Нет'; }

lections = function ( data, type, full, meta )
    { return `<a href="/lections/lesson_id/${full.id}">${data}</a>`; }
    
    show_inventarisation = function ( data, type, full, meta )
    { return `${data?data.Наименование:''}`; }

    columns = [
      {data: "Код_Работы", "name": "Код_Работы", title: "Код_Работы", visible:false},
      {data: "id", "name": "id", title: "id", visible:false},      
      {data: "Наименование","name": "Наименование",title: "Наименование"},
      {data: "Стоимость", "name": "Стоимость", title: "Стоимость"},
      {data: "Скидка", "name": "Скидка", title: "Скидка"},
      {data: "inventarisation", "name": "Вид_КО", title: "Вид_КО", "render":show_inventarisation},
      {data: "Адрес_Объекта", "name": "Описание объекта", title: "Описание объекта"},
      {data: "Дата_Выдачи_Работы", "name": "Дата_Выдачи_Работы", title: "Дата_Выдачи_Работы"}, 
      
  ]