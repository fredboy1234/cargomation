$(document).ready(function () {
    hashPop();
    var loader = '<div id="loader-wrapper" class="d-flex justify-content-center">' +
                '<div class="spinner-border" role="status">' +
                '<span class="sr-only">Loading...</span>' +
                '</div>' +
                '</div>';

    $("button.action").click(function(event) {
        var url = "docdeveloper/" + this.dataset.action + "/" + this.dataset.id;
        switch (this.dataset.action) {
            case "view":
                preloader(url);
                break;
            case "edit":
                preloader(url);
                break;
            case "delete":
                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure you want to delete this account?',
                    text: 'Account will be deleted and you won\'t be able to revert this!',
                    showConfirmButton: true,
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: `Delete`,
                    denyButtonText: `Delete`,
                }).then((result) => {
                    /* I use isDenied, coz of the color */
                    if (result.isConfirmed) {
                        $.post( "contact/delete/" + this.dataset.id, function() {
                            Swal.fire('Account deleted!', 'This page will be refreshed.', 'success');
                        }).done(function() {
                            location.reload();
                            // alert( "second success" );
                        }).fail(function() {
                            // alert( "error" );
                        }).always(function() {
                            // alert( "finished" );
                        });
                    }
                    if (result.isDenied) {
                        resolve();
                    } else if (result.isDismissed) {
                        Swal.fire('Account deletion was aborted!', 'Account "' + this.dataset.id + '" was not deleted.', 'info');
                    }
                });
                break;
            default:
                break;
        }

    })

    $('#filetrainer').submit(function(event) {
        event.preventDefault();

        // var data = $(this).serializeArray();

        $.ajax({
            url: '/docdeveloper/learn',
            type: "POST",
            dataType: "json",
            data: $(this).serializeArray(),
            beforeSend: function () {
                $("#filetrainer").find(":submit").prop('disabled', true);
                $("#filetrainer .card-body").append('<center id="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>');
            }, success: function (result) {
                $('#loader').remove();
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'File was train successfully!',
                    timer: 3000
                });
                console.log(result);
                $("#filetrainer ").find(":submit").prop('disabled', false);
            }
        });
       
    })

});

// Show loader
function preloader(url) {

    var loader = '<div id="loader-wrapper" class="d-flex justify-content-center">' +
            '<div class="spinner-border" role="status">' +
            '<span class="sr-only">Loading...</span>' +
            '</div>' +
            '</div>';

    $("body").append(loader);

    // load the url and show modal on success
    $("#myModal .modal-body").load(url,
        function (response, status, xhr) {
            if (xhr.status == 200) {
                $('#loader-wrapper').remove();
                $("#myModal").modal("show");
            } else {
                alert("Error: " + xhr.status + ": " + xhr.statusText);
                $('#loader-wrapper').remove();
            }
        });

    //$('button#request').toggle();
}

function hashPop() {
    var hash = window.location.hash;
    hash && $('ul.nav a[href="' + hash + '"]').tab('show');

    $('.nav-tabs a').click(function (e) {
        $(this).tab('show');
        var scrollmem = $('body').scrollTop();
        window.location.hash = this.hash;
        $('html,body').scrollTop(scrollmem);
    });
}

$(window).bind('hashchange', function() {
    hashPop() 
});

$(window).bind('popstate', function() {
    hashPop() 
});