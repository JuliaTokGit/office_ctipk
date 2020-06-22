$(document).ready(function () {
    var options =
    {
        thumbBox: '.thumbBox',
        spinner: '.spinner',
        imgSrc: 'avatar.png'
    }
    $('.imagecrop').on('change', function(){
        var reader = new FileReader();
        var cbox=$(this).data('box');
        reader.onload = function(e) {
            options.imgSrc = e.target.result;
            // console.log(cbox);
            cropper = $(cbox).cropbox(options);
        }
        reader.readAsDataURL(this.files[0]);
        // this.files = [];
        $(cbox).show();
        $('.imageBox_controls').show();
        $(this).addClass('cropped');
    })
    $('.btnCrop').on('click', function(){
        // var img = cropper.getDataURL();
        // $('#hiddenfile').val(img);
    })
    $('.btnZoomIn').on('click', function(){
        cropper.zoomIn();
    })
    $('.btnZoomOut').on('click', function(){
        cropper.zoomOut();
    })
});