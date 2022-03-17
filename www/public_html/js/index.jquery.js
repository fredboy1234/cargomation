class Cargomation{
    library = [];

    constructor(){
        var self =this;
        self.library.data = "test";
    }

    notification(){
       
    }

    process(tab){
        
    }

    getDateFormatString(locale,extension='') {
    
        const options = {
          day: "numeric",
          month: "numeric",
          year: "numeric",
        };

        if(extension =='hasTime'){
            options = {
          hour: "numeric",
          minute: "numeric",
          second: "numeric",
          day: "numeric",
          month: "numeric",
          year: "numeric",
        };
        }
      
        const formatObj = new Intl.DateTimeFormat(locale, options).formatToParts(
          Date.now()
        );
        
        return formatObj
          .map((obj) => {
            switch (obj.type) {
              case "hour":
                return "HH";
              case "minute":
                return "MM";
              case "second":
                return "SS";
              case "day":
                return "DD";
              case "month":
                return "MM";
              case "year":
                return "YYYY";
              default:
                return obj.value;
            }
          })
          .join("");
    }

    scriptloading(url=''){
      var script = document.createElement('script'),
      scripts = document.getElementsByTagName('script')[0];
      script.src = url;
      scripts.parentNode.insertBefore(script, scripts);
    }

    chatsubmit(){
      $("#contact-form").submit(function (e) {
        var data = $(this).serializeArray();
        var email = data[0]['value'];
        var name = data[1]['value'];
        var message = data[2]['value'];

        $.ajax({
            url: '/contact/sendEmailAPI',
            type: "POST",
            dataType: "json",
            data: {
                'name': name,
                'email': email,
                'message': message
            },
            beforeSend: function () {
                $("#contact-form").find(":submit").prop('disabled', true);
                $("#contact-form .card-body").append('<center id="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>');
            }, success: function (result) {
                $('#loader').remove();
                Swal.fire({
                    icon: 'success',
                    title: 'Message Sent!',
                    text: 'Your message was sent!',
                    timer: 3000
                });
                $('#myForm').find('input:text, input:password, input, select, textarea')
                            .each(function () {
                                $(this).val('');
                            });
                $("#contact-form").find(":submit").prop('disabled', false);
                closeForm();
            }
        });
        e.preventDefault();
      });

      function openForm() {
        document.getElementById("myForm").style.display = "block";
      }

      function closeForm() {
        document.getElementById("myForm").style.display = "none";
      }

    }
}