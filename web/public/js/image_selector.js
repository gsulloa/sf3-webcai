/**
 * Created by gsull on 01-01-2016.
 */
//ImageSelector
var selected_image;
var row_changing;
$('.selecting_image').click(function () {
    $('.col_selecting_image').css('background-color','white');
    $(this).parent().css('background-color','blue');
    selected_image = $(this)[0];
});
$('.selecting_image').dblclick(function () {
    changeImage();
});
function changeImage(){
    hide_image_selector();
    var imagen =  $('.row#'+row_changing).children(".col-md-4").children().children();
    imagen.attr('src',selected_image.src);
    console.log(imagen[0].id);
    $('input#'+imagen[0].id).attr('value',selected_image.id);

}
$('.img_selector').click(function () {
    show_image_selector($(this));
});
$('#image_selector_background').click(function () {
    hide_image_selector();
});
//Muestra el selector de imagenes
function show_image_selector(e){
    row_changing = e.parent().parent().parent()[0].id;
    console.log(row_changing);
    $("#image_selector_div").show(500);
    $("#image_selector_background").show();
}
//Esconde el selector de imagenes
function hide_image_selector(){
    $("#image_selector_div").hide(500);
    $("#image_selector_background").hide();
    $('.col_selecting_image').css('background-color','white');
}

/*<div class="col-md-3 col_selecting_image">
    <img id="image_6134" class="selecting_image img-responsive" src="/sf2-webCAi/web/uploads/biblioteca/imagenes/49356a8f1d35ff084ef1ac9365dcaddd923b18e1/small-love.jpeg">
    </div>*/