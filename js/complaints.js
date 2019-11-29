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
        var rowID = $(this).attr('data-id');
        var dialogRow = $( "#dialog_" + rowID );
        $("#row_" + rowID).css("background-color", "#fff9c7");
        dialogRow.dialog( "open");
        dialogRow.on( "dialogbeforeclose", function() {
            $("#row_" + rowID).css("background-color", "");
        } );
    });
})( jQuery );