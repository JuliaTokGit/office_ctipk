
$(function(){
  jQuery.fn.extend({select2:null}); // Убрал криво работающий в jsoneditor select2
  if ($('#json_editor').length){

    if (collection_name=='pages'){url='page';}
    if (collection_name=='layouts'){url='layout';}
    if (collection_name=='common'){
      url=id.split("-")[0];
    }
    

    $.getJSON('/schemas/id/'+url, function (schema){
      
      var editor = new JSONEditor(document.getElementById("json_editor"),{
        ajax:true,
        theme:'bootstrap3',
        iconlib:'fontawesome4',
        schema:schema,
        startval: config,
        'object_layout': "grid",
        'array_layout': "grid"
      });

      editor.on('ready',function() {
        $('#json_editor').find('.loading').hide();
        $('.json-editor-btn-collapse').each(function(){
          $(this).click();
        });
        $('#json_editor').find('h3').first().append('<div class="btn-group" style="margin-left: 10px;"><button class="btn btn-default json-editor-btn-submit ">Сохранить конфигурацию</button></div>');
        $('.json-editor-btn-submit').on('click',function() {
          
          var errors = editor.validate();
          if(errors.length) {
            console.log(errors);
            alert('Исправьте ошибки!');
          }
          else {
            $.ajax({
              method:"POST",
              dataType:'json',
              data: { action: "save", data: JSON.stringify(editor.getValue()) }
            }).done(function() {
              alert('Сохранено!');
            });
          }

        });
      });

    });
  }
})