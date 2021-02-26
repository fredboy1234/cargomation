$(document).ready(function(){
    $("#contact-form").submit(function(e){
       
        var data = $(this).serializeArray();
        var email = data[0]['value'];
        var name = data[1]['value'];
        var message = data[2]['value'];
       
        $.ajax({
            url:'/contact/sendEmail',
            type: "POST",
            dataType:"json",
            data:{
                'name': name,
                'email': email,
                'message': message
            },success: function(result){
                console.log(result);
            }
        });
        e.preventDefault();
    });
});