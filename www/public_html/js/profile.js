$(document).ready(function () {
    hashPop();
    var loader = '<div id="loader-wrapper" class="d-flex justify-content-center">' +
                '<div class="spinner-border" role="status">' +
                '<span class="sr-only">Loading...</span>' +
                '</div>' +
                '</div>';

    $(".profile-icon").on("click", function () {
        $("#profileModal .modal-body").load("profile/profileImageList");
        $("#profileModal").modal("show");
    });

    $("#hfupload, #footerupload").on("click", function () {
        $("#hfmodal .modal-body").load("profile/miscImageList");
        $("#hfmodal").modal("show");
        $("#hfmodal").attr("data-cid",$(this).attr('id'));
    });
    

    $("#settings > form").submit(function( event ) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: document.location.origin + "/profile/saveSettings",
            data: $(this).serializeArray(),
            dataType: "JSON",
            beforeSend: function () {
                $("body").append(loader);
            },
            success: function (res) {
                console.log(res);
                if(res.status == "success") {
                    $('#loader-wrapper').remove();
                    window.history.pushState("", "", '/profile#settings'); 
                } else {
                    console.log(res.message);
                }
            }
        });
    });

    $("#edit-settings > form").submit(function( event ) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: document.location.origin + "/profile/updateProfile",
            data: $(this).serializeArray(),
            dataType: "JSON",
            beforeSend: function () {
                $("body").append(loader);
            },
            success: function (res) {
                console.log(res);
                if(res.status == "success") {
                    //$('#loader-wrapper').remove();
                    //window.history.pushState("", "", '/profile#information');
                    window.location.href = "/profile";
                } else {
                    console.log(res.message);
                }
            }
        });
    });

    $("button.action").click(function(event) {
        var url = "contact/" + this.dataset.action + "/" + this.dataset.id;
        switch (this.dataset.action) {
            case "show":
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

    // $("#upload-image").fileinput({
    //     //showPreview: false,
    //     previewFileType: "image",
    //     browseClass: "btn btn-primary btn-block",
    //     browseLabel: "Upload Photo",
    //     browseIcon: "<i class=\"fas fa-cloud-upload-alt\"></i> ",
    //     showCaption: false,
    //     showRemove: false,
    //     showUpload: false,
    //     allowedFileExtensions: ["jpg", "gif", "png"],
    // }).on('fileloaded', function(event, file, previewId, fileId, index, reader) {
    //     // $('#' + previewId).find('img.file-preview-image').JCrop({
    //     //     onSelect:function(c){
    //     //         console.log(c);
    //     //     }
    //     // });
    //     //Jcrop.attach('img.file-preview-image');

    //     console.log(previewId);
    //     console.log("olok")
    // });

    $('#defaultDash').on('change',function() {
        var dashSet = '';
        if(this.checked) {
            dashSet = 'dash_v1'
        }
        $.ajax({
            type: "POST",
            url: document.location.origin + "/profile/saveSettings",
            data:{'dash':dashSet,'column':'dashboard'},
            dataType: "JSON",
            success: function (res) {
                console.log(res);
            }
        });
    });
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