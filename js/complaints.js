(function($) {
    $( ".dialog" ).dialog(
        {
            autoOpen: false,
            width: 600,
            show: {
                effect: "blind",
                duration: 1000
            },
            hide: {
                effect: "blind",
                duration: 1000
            }
        });
    $('.open_view').click(function(){
        var dialogID = $(this).attr('data-id');
        $( "#dialog_"+dialogID ).dialog( "open");
    });
})( jQuery );