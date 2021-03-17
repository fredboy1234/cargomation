$(document).ready(function () {
    $("#contact-form").submit(function (e) {

        var data = $(this).serializeArray();
        var email = data[0]['value'];
        var name = data[1]['value'];
        var message = data[2]['value'];

        $.ajax({
            url: '/contact/sendEmail',
            type: "POST",
            dataType: "json",
            data: {
                'name': name,
                'email': email,
                'message': message
            },
            beforeSend: function () {
                $("#contact-form .card-body").append('<center id="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>');
            }, success: function (result) {
                $('#loader').remove();
                Swal.fire({
                    icon: 'success',
                    title: 'Message Sent!',
                    text: 'Your message was sent!',
                    timer: 3000
                });
                console.log(result);
            }
        });
        e.preventDefault();
    });
});