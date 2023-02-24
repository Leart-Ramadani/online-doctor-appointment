$(document).ready(function() {
    $('.anuloPop').click(function(r) {
        r.preventDefault();

        let id_anulo = $(this).closest('tr').find('.idAnulo').text();

        $.ajax({
            type: "POST",
            url: "requestCancelation.php",
            data: {
                'popAnulo': true,
                'idAnulo': id_anulo,
            },
            success: function(response) {
                console.log(response);
                $('.doc_pac').html(response);
            }

        });


    });
});