
$(document).ready(function(){

    $('.popUpWindow').click(function () {
        $('#popWindow').addClass('popWindow');
        $('#popWrapper').addClass('wrapperWindow');
    });

    $('.close').click(function () {
        $('#popWindow').removeClass('popWindow');
        $('#popWrapper').removeClass('wrapperWindow');
    });

    $('.anuloPop').click(function () {
        $('#popWindow').addClass('popWindow');
        $('#popWrapper').addClass('wrapperWindow');
    });

    $('.close').click(function () {
        $('#popWindow').removeClass('popWindow');
        $('#popWrapper').removeClass('wrapperWindow');
        $('.bookApp').addClass('disabled');
    });

    $('.close').click(function () {
        $('.njoftim').removeClass('njoftim');
        $('#popWrapper').removeClass('wrapperWindow');
    });


    $('#ham_menu').click(function(){
        $('.sidebar').addClass('side_res');
        $('#ham_menu').addClass('hamburger');
    });

    $('.close_side').click(function(){
        $('.sidebar').removeClass('side_res');
        $('#ham_menu').removeClass('hamburger');
    });

});


