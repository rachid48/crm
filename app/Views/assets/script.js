$( document ).ready(function () {

    $('.js-check-email').click(function (e) {
        e.preventDefault();
        var email = $('input[type="email"]').val();
        $.ajax({
            type: 'POST',
            url: 'index.php?p=api.checkemail&email='+email,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            success: function(response) {
                if(response.response.valide === true){
                    $('.email-valide').html('Adresse email valide !');
                    $('.email-error').html('');

                } else {
                    $('.email-error').html('Adresse email invalide !');
                    $('.email-valide').html('');
                }
            },
            error: function(error) {
                console.log(error);
            }
        });


    })
});