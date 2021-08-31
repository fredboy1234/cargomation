$(document).ready(function () {
    var loader = '<div id="loader-wrapper" class="d-flex justify-content-center">' +
                '<div class="spinner-border" role="status">' +
                '<span class="sr-only">Loading...</span>' +
                '</div>' +
                '</div>';

    $(".profile-icon").on("click", function () {
        $("#profileModal .modal-body").load("profile/profileImageList");
        $("#profileModal").modal("show");
    });

    $("#settings > form").submit(function( event ) {
        console.log('subbbb');
        // $("body").append(loader);
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
});

$(function () {
    var hash = window.location.hash;
    hash && $('ul.nav a[href="' + hash + '"]').tab('show');

    $('.nav-tabs a').click(function (e) {
        $(this).tab('show');
        var scrollmem = $('body').scrollTop();
        window.location.hash = this.hash;
        $('html,body').scrollTop(scrollmem);
    });
});