$(document).ready(function(){
    /* =====================================================================
     MAKE NAV WHITE
     ===================================================================== */
    $(window).scroll(function(){
        if($(document).scrollTop() > 100) {
            $('#mainNav').addClass('white');
        } else {
            $('#mainNav').removeClass('white');
        }
    });
});