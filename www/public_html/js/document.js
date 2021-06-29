
$(document).ready(function () {

    console.log(document_type);

    if (document_type === "") {
        console.log('empty');
        $('button#request').hide();
    } else {
        console.log(document_type);
        $('button#request').show();
    }

    const DEFAULT_PREVIEW = '<div class="file-preview-other">\n' +
        '   <span class="{previewFileIconClass}">{previewFileIcon}</span>\n' +
        '</div>';
    var loader = '<div id="loader-wrapper" class="d-flex justify-content-center">' +
        '<div class="spinner-border" role="status">' +
        '<span class="sr-only">Loading...</span>' +
        '</div>' +
        '</div>';

    $('.file-loading').show();

    // Bootstrap File Input
    var $el1 = $("#input");
    $el1.fileinput({
        theme: 'fas',
        uploadUrl: document.location.origin + "/api/post/document/upload" + param,
        deleteUrl: document.location.origin + "/api/post/document/delete" + param,
        uploadAsync: false,
        allowedPreviewMimeTypes: null,
        initialPreviewAsData: true,
        initialPreviewFileType: ['pdf', 'office', 'gdocs', 'other'],
        initialPreview: initialPreview,
        initialPreviewAsData: initialPreviewAsData,
        initialPreviewFileType: initialPreviewFileType,
        initialPreviewConfig: initialPreviewConfig,
        initialPreviewShowDelete: delete_button,
        initialPreviewThumbTags: initialPreviewThumbTags,
        initialPreviewDownloadUrl: '/404.html',
        previewFileIcon: '<i class="fas fa-file"></i>',
        previewFileIconSettings: {
            'docx': '<i class="fas fa-file-word text-primary"></i>',
            'xlsx': '<i class="fas fa-file-excel text-success"></i>',
            'pptx': '<i class="fas fa-file-powerpoint text-danger"></i>',
            'jpg': '<i class="fas fa-file-image text-warning"></i>',
            'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
            'zip': '<i class="fas fa-file-archive text-muted"></i>',
        },
        showPreview: true,
        initialPreviewDownloadUrl: false,
        actionDownload: false,
        enableResumableUpload: false,
        resumableUploadOptions: {
            // uncomment below if you wish to test the file for previous partial uploaded chunks
            // to the server and resume uploads from that point afterwards
            // testUrl: "http://localhost/test-upload.php"
        },
        uploadExtraData: {
            // extra data can be inserted here like document_id, user_id
            'uploadToken': 'SOME-TOKEN', // for access control / security 
        },
        // uploadExtraData: function() {
        //     return {
        //         _token: '<?php echo csrf_token() ?>',
        //     };
        // },
        showPreview: true,
        showUpload: true, // hide upload button
        showRemove: false, // hide remove button
        showDownload: false,
        overwriteInitial: false, // append files to initial preview
        minFileCount: 1,
        maxFileCount: 5,
        browseOnZoneClick: true,
        fileActionSettings: {
            showDrag: true,
            showZoom: true,
            showUpload: false,
            showDelete: false,
            showDownload: false,
            // removeTitle: 'Remove File',
            // uploadTitle: 'Upload File',
            // downloadTitle: 'Download file',
        },
        // previewTemplates: {
        //     pdf: '<div class="file-preview-frame {frameClass}" id="{previewId}" data-fileindex="{fileindex}" data-template="{template}">\n' +
        //         '   <div class="checkbox">' +
        //         '       <input class="form-check-input" type="checkbox" value="{id}">' +
        //         '   </div>' +
        //         '   <div class="kv-file-content">' +
        //         '       <object class="kv-preview-data file-preview-pdf" title="{caption}" data="{data}" type="application/pdf" {style}>\n' +
        //         //DEFAULT_PREVIEW +
        //         '</object>\n' +
        //         '   </div>\n' +
        //         '   {footer}\n' +
        //         '</div>',
        //     other: '<div class="file-preview-frame {frameClass}" id="{previewId}" data-fileindex="{fileindex}" data-template="{template}" title="{caption}">\n' +
        //         '   <div class="kv-file-content">' +
        //         '       <div class="kv-preview-data file-preview-other-frame" {style}>\n' +
        //         '           <div class="file-preview-other">\n' +
        //         '              {previewFileIcon}\n' +
        //         '           </div>\n' +
        //         '       </div>\n' +
        //         '   </div>\n' +
        //         '   <div class="file-preview-other-footer">{footer}</div>\n' +
        //         '</div>'
        // },
        layoutTemplates: {
            footer: 
            '<div class="icheck-primary checkbox d-inline">' +
            '    <input type="checkbox" id="checkbox-{id}" value="{id}">' +
            '    <label for="checkbox-{id}">' +
            '    </label>' +
            '</div>' +
            '<div class="file-thumbnail-footer">\n' +
                '    <div class="file-footer-caption" title="{caption}">File Name: {caption}' +
                '       <br>File Type: <b>{type}</b>' +
                '       <br>File Source: <b>{origin}</b>' +
                '       <br>File Status: <b id="status" class="{status}">{status}</b>' +
                '   </div>\n' +
                '    {progress} {indicator} {actions}\n' +
                '</div>'
        },
        // layoutTemplates: {
        //     main1: '{preview}\n' +
        //         '<div class="kv-upload-progress kv-hidden"></div><div class="clearfix"></div>\n' +
        //         '<div class="input-group {class}">\n' +
        //         '  {caption}\n' +
        //         '  <div class="input-group-btn">\n' +
        //         '    {remove}\n' +
        //         '    {cancel}\n' +
        //         '    {pause}\n' +
        //         '    {upload}\n' +
        //         '    {browse}\n' +
        //         '  </div>\n' +
        //         '</div>',
        //     main2: '{preview}\n<div class="kv-upload-progress hide"></div>\n{remove}\n{cancel}\n{upload}\n{browse}\n',
        //     preview: '<div class="file-preview {class}">\n' +
        //         '    {close}\n' +
        //         '    <div class="close fileinput-remove">×</div>\n' +
        //         '    <div class="{dropClass}">\n' +
        //         '    <div class="file-preview-thumbnails">\n' +
        //         '    </div>\n' +
        //         '    <div class="clearfix"></div>' +
        //         '    <div class="file-preview-status text-center text-success"></div>\n' +
        //         '    <div class="kv-fileinput-error"></div>\n' +
        //         '    </div>\n' +
        //         '</div>',
        //     icon: '<span class="glyphicon glyphicon-file kv-caption-icon"></span>',
        //     caption: '<div class="file-caption form-control {class}" tabindex="500">\n' +
        //         '  <span class="file-caption-icon"></span>\n' +
        //         '  <input class="file-caption-name" onkeydown="return false;" onpaste="return false;">\n' +
        //         '</div>',
        //     btnDefault: '<button type="{type}" tabindex="500" title="{title}" class="{css}"{status}>{icon}{label}</button>',
        //     btnLink: '<a href="{href}" tabindex="500" title="{title}" class="{css}"{status}>{icon}{label}</a>',
        //     btnBrowse: '<div tabindex="500" class="{css}"{status}>{icon}{label}</div>',
        //     modalMain: '<div id="kvFileinputModal" class="file-zoom-dialog modal fade" tabindex="-1" aria-labelledby="kvFileinputModalLabel"></div>',
        //     modal: '<div class="modal-dialog modal-lg{rtl}" role="document">\n' +
        //         '  <div class="modal-content">\n' +
        //         '    <div class="modal-header">\n' +
        //         '      <div class="kv-zoom-actions pull-right">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
        //         '      <h3 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h3>\n' +
        //         '    </div>\n' +
        //         '    <div class="modal-body">\n' +
        //         '      <div class="floating-buttons"></div>\n' +
        //         '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
        //         '    </div>\n' +
        //         '  </div>\n' +
        //         '</div>\n',
        //     progress: '<div class="progress">\n' +
        //         '    <div class="progress-bar progress-bar-success progress-bar-striped text-center" role="progressbar" aria-valuenow="{percent}" aria-valuemin="0" aria-valuemax="100" style="width:{percent}%;">\n' +
        //         '        {status}\n' +
        //         '     </div>\n' +
        //         '</div>\n' +
        //         '{stats}',
        //     stats: '<div class="text-info file-upload-stats">' +
        //         '<span class="pending-time">{pendingTime}</span> ' +
        //         '<span class="upload-speed">{uploadSpeed}</span>' +
        //         '</div>',
        //     footer: '<div class="file-thumbnail-footer">\n' +
        //         '    <div class="file-caption-name" style="width:{width}">{caption}</div>\n' +
        //         '    {progress} {actions}\n' +
        //         '</div>',
        //     actions: '<div class="file-actions">\n' +
        //         '    <div class="file-footer-buttons">\n' +
        //         '        {upload} {download} {delete} {zoom} {other}' +
        //         '    </div>\n' +
        //         '    {drag}\n' +
        //         '    <div class="file-upload-indicator" title="{indicatorTitle}">{indicator}</div>\n' +
        //         '    <div class="clearfix"></div>\n' +
        //         '</div>',
        //     actionDelete: '<button type="button" class="kv-file-remove {removeClass}" title="{removeTitle}"{dataUrl}{dataKey}>{removeIcon}</button>\n',
        //     actionUpload: '<button type="button" class="kv-file-upload {uploadClass}" title="{uploadTitle}">{uploadIcon}</button>\n',
        //     actionDownload: '<button type="button" class="{downloadClass}" title="{downloadTitle}" data-url="{downloadUrl}" data-caption="{caption}">{downloadIcon}</button>\n',
        //     actionZoom: '<button type="button" class="kv-file-zoom {zoomClass}" title="{zoomTitle}">{zoomIcon}</button>',
        //     actionDrag: '<span class="file-drag-handle {dragClass}" title="{dragTitle}">{dragIcon}</span>'
        // },
        otherActionButtons: upload_button +
            '<button type="button" ' +
            'class="kv-file-edit btn btn-sm btn-kv btn-default btn-outline-secondary" ' +
            'title="Request for Edit"{dataUrl}{dataKey} ' +
            'data-doc_id="{id}" data-doc_status="{status}">' +
            '<i class="fas fa-edit"></i>' +
            '</button>\n' +
            '<button type="button" ' +
            'class="kv-file-comment btn btn-sm btn-kv btn-default btn-outline-secondary" ' +
            'title="View Comment"{dataUrl}{dataKey} ' +
            'data-doc_id="{id}" data-doc_status="{status}">' +
            '<i class="fas fa-comment"></i>' +
            '</button>\n' +
            '<button type="button" ' +
            'class="kv-file-status btn btn-sm btn-kv btn-default btn-outline-secondary" ' +
            'title="Change Status"{dataUrl}{dataKey} ' +
            'data-doc_id="{id}" data-doc_status="{status}">' +
            '<i class="fas {icon} {status}"></i>' +
            '</button>\n',
    }).on("filebatchselected", function (event, files) {
        var out = '';
        $.each(files, function (key, file) {
            var fname = file.name;
            out = out + '<li>' + '<b>File: </b>' + (key + 1) + ' - ' + fname + '.' + '</li>';
        });
        Swal.fire({
            icon: "info",
            title: "Are you sure you want to upload?",
            text: 'TEST',
            html: out,
            showCancelButton: true,
            confirmButtonText: 'Upload',
        }).then((result) => {
            if (result.isConfirmed) {
                $el1.fileinput("upload");
            } else {
                Swal.fire('File upload was aborted!', '', 'info');
            }
        });
    }).on('filebatchuploadsuccess', function (event, data) {
        var out = '';
        $.each(data.files, function (key, file) {
            var fname = file.name;
            out = out + '<li>' + '<b>File # ' + (key + 1) + '</b> - ' + fname + '</li>';
        });
        Swal.fire({
            icon: 'success',
            title: 'Uploaded successfully!',
            html: out,
        }).then((result) => {
            if (result.isConfirmed) {
                setTimeout(function () {
                    $('.kv-upload-progress').fadeOut('slow');
                }, 900);
            }
        });
        var url = "shipment/document/" + shipment_id + "/" + document_type;
        preloader(url);
        // $('#kv-success-2 ul').append(out);
        // $('#kv-success-2').fadeIn('slow');
    }).on('filebatchuploadcomplete', function (event, preview, config, tags, extraData) {
        // alert('filebatchuploadcomplete');
        // console.log('File Batch Uploaded', preview, config, tags, extraData);
        // setTimeout(function () {
        //     $('.kv-upload-progress').fadeOut('slow');
        // }, 2000);
    }).on('filebeforedelete', function (event, key, data) {
        // var aborted = !window.confirm('Are you sure you want to delete this file?');
        // if (aborted) {
        //     window.alert('File deletion was aborted! ');
        //     console.log('File deletion was aborted : ' + key);
        // };
        // console.log(aborted);
        // return aborted;
        // To bypass interface-blocking of swal, I need to use Promise for async process
        return new Promise(function (resolve, reject) {
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this file?',
                text: '"' + data.name + '" will be deleted and you won\'t be able to revert this!',
                showConfirmButton: false,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Delete`,
                denyButtonText: `Delete`,
            }).then((result) => {
                /* I use isDenied, coz of the color */
                if (result.isDenied) {
                    resolve();
                } else if (result.isDismissed) {
                    Swal.fire('File deletion was aborted!', 'File "' + data.name + '" was not deleted.', 'info');
                }
            });
            // $.confirm({
            //     title: 'Confirmation!',
            //     content: 'Are you sure you want to delete this file?',
            //     type: 'red',
            //     buttons: {
            //         ok: {
            //             btnClass: 'btn-primary text-white',
            //             keys: ['enter'],
            //             action: function () {
            //                 resolve();
            //             }
            //         },
            //         cancel: function () {
            //             $.alert('File deletion was aborted! ' + krajeeGetCount('file-6'));
            //         }
            //     }
            // });
        });

    }).on('filedeleted', function (event, key, jqXHR, data) {
        // setTimeout(function () {
        //     window.alert('File deletion was successful! ');
        //     console.log('Deleted file : ' + key);
        // }, 900);
        Swal.fire({
            icon: 'success',
            title: 'File "' + data.name + '" was deleted successful!',
            showConfirmButton: false,
            timer: 1500
        })
    }).on('filepreupload', function (event, data, previewId, index, fileId) {
        alert('filepreupload');
        // var form = data.form, files = data.files, extra = data.extra,
        //     response = data.response, reader = data.reader;
        // console.log('File pre upload triggered', fileId);
    }).on('fileuploaded', function (event, data, id, index) {
        alert('THIS IS BATCH');
        // var fname = data.files[index].name,
        //     out = '<li>' + 'Uploaded file # ' + (index + 1) + ' - ' + fname + ' successfully.' + '</li>';
        // Swal.fire(out);
        // // $(document).Toasts('create', {
        // //     title: 'Success',
        // //     body: out,
        // //     autohide: true,
        // //     close: false,
        // //     class:'bg-success'
        // // });
        // //   $('#kv-success-1').append(out);
        // //   $('#kv-success-1').fadeIn('slow');
        // setTimeout(function () {
        //     // $('#kv-success-1').fadeOut('slow');
        //     $('.kv-upload-progress').fadeOut('slow');
        // }, 3000);
    }).on('fileuploaderror', function (event, data, msg) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
            footer: '<a href>Why do I have this issue?</a>'
        });
        console.log('File upload error: ' + msg);
    }).on('filecustomerror', function (event, data, msg) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: msg,
            footer: '<a href>Why do I have this issue?</a>'
        });
        console.log('File upload error: ' + msg);
    }).on('filebatchuploaderror', function (event, data, msg) {
        Swal.fire({
            icon: 'error',
            title: 'BATCH Oops...',
            text: 'Something went wrong!',
            footer: '<a href>Why do I have this issue?</a>'
        });
        console.log('File upload error: ' + msg);
    });

    // Button Upload
    $('button.kv-file-push').click(function () {
        Swal.fire({
            title: "Are you sure?",
            text: "This action will push the file to CargoWise, Still want to continue?",
            icon: "warning",
        }).then((willDelete) => {
            if (willDelete) {
                var doc_id = $(this).data("doc_id");
                $.ajax({
                    type: "POST",
                    url: "/document/pushToCargoWise",
                    ContentType: 'application/json',
                    data: { "user_id": user_id, "doc_id": doc_id },
                    success: function (response) {
                        // console.log(response);
                        Swal.fire("Push success!", "File successfully uploaded to CargoWise!", "success");
                    }
                });
            } else {
                Swal.fire("You cancel your action!");
            }
        });
    });

    // Button Status
    $('button.kv-file-status').click(function () {
        var doc_status = $(this).data("doc_status");
        var doc_id = $(this).data("doc_id");
        var btn_color = '#dc3545';
        if (doc_status === 'approved') {
            doc_status = 'pending';
            btn_color = '#dc3848';
            btn_icon = 'down';
        } else {
            doc_status = 'approved';
            btn_color = '#29a847';
            btn_icon = 'up';
        }
        var msg = 'This will change the status of the document to "' + doc_status.charAt(0).toUpperCase() + doc_status.slice(1) + '". Do you still want to continue?';
        Swal.fire({
            title: 'Are you sure?',
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: doc_status.charAt(0).toUpperCase() + doc_status.slice(1) + " " + '<i class="fa fa-thumbs-' + btn_icon + '"></i>',
            confirmButtonColor: btn_color,
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.post("/document/updateDocumentStatus", { doc_status: doc_status, doc_id: doc_id })
                    .done(function () {
                        Swal.fire({
                            title: 'Do you want to leave a comment?',
                            icon: 'question',
                            showDenyButton: true,
                            showCancelButton: true,
                            confirmButtonText: `Leave comment`,
                            denyButtonText: `No`,
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                Swal.fire('Status changed!', 'You will be redirected to comment form.', 'success');
                                var url = "/document/comment/" + doc_id + "/" + doc_status;

                                // load the url and show modal on success
                                preloader(url);
                            } else if (result.isDenied) {
                                Swal.fire('Status changed!', 'Save but no comment made.', 'info')
                                $.post("/document/putDocumentComment", {
                                    title: "",
                                    message: "",
                                    status: doc_status,
                                    user_id: user_id,
                                    document_id: doc_id
                                });
                            } else {
                                Swal.fire('Status changed!', 'Save but no comment made.', 'info')
                                $.post("/document/putDocumentComment", {
                                    title: "",
                                    message: "",
                                    status: doc_status,
                                    user_id: user_id,
                                    document_id: doc_id
                                });
                            }
                            var d = $('.d-' + doc_id);
                            if (d.attr('class').indexOf('bg-') === -1) { d.addClass("bg-" + doc_status); }
                            else { $('.bg-approved, .bg-pending').toggleClass('bg-approved bg-pending'); }
                            d.toggleClass("b-approved b-pending");
                            $('[data-key="' + doc_id + '"]').attr("data-doc_status", doc_status);
                            d.find('.kv-file-status').children().toggleClass("approved fa-thumbs-up pending fa-thumbs-down");
                            d.find('.file-footer-caption > #status').toggleClass("approved pending").text(valueSelected);
                        })
                    }).fail(function (response) {
                        console.log('Error: ' + response.responseText);
                    });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Document was set to ' + doc_status.charAt(0).toUpperCase() + doc_status.slice(1),
                    showConfirmButton: false,
                    timer: 1500
                });

                // alert('Please leave a note for the ' + doc_status + ' document');
                // if (window.confirm("Do you want to leave a comment?")) {
                //     var url = "/document/comment/" + doc_id + "/" + doc_status;

                //     // load the url and show modal on success
                //     preloader(url);
                // } else {
                //     // $.post( "document/putDocumentComment", { user_id: "1", doc_id: "10001" } );
                //     $.post("/document/putDocumentComment", {
                //         title: "",
                //         message: "",
                //         status: doc_status,
                //         user_id: user_id,
                //         document_id: doc_id
                //     });
                // }

                //find('i').toggleClass("fa-thumbs-up fa-thumbs-down");
                //.children().toggleClass("approved pending").data("doc_status", doc_status);

            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire(
                    'Cancelled',
                    'No status change in the document',
                    'error'
                )
            }
        })




        // var msg = confirm("This will change the status of the document to " + doc_status.charAt(0).toUpperCase() + doc_status.slice(1) + " . Do you still want to continue?");

        // if (msg == true) {
        //     $.ajax({
        //         type: "POST",
        //         url: "/document/updateDocumentStatus",
        //         ContentType: 'application/json',
        //         data: { "doc_status": doc_status, "doc_id": doc_id },
        //         success: function (response) {
        //             //alert('Document was set to ' + doc_status.charAt(0).toUpperCase() + doc_status.slice(1));
        //             $(document).Toasts('create', {
        //                 title: 'Success',
        //                 body: 'Document was set to ' + doc_status.charAt(0).toUpperCase() + doc_status.slice(1),
        //                 autohide: true,
        //                 close: false,
        //                 class: 'bg-success'
        //             }).on('hidden.bs.toast', function () {
        //                 // REMOVE FUNCTION    
        //             });
        //             // alert('Please leave a note for the ' + doc_status + ' document');
        //             if (window.confirm("Do you want to leave a comment?")) {
        //                 var url = "/document/comment/" + doc_id + "/" + doc_status;

        //                 // load the url and show modal on success
        //                 preloader(url);
        //             } else {
        //                 // $.post( "document/putDocumentComment", { user_id: "1", doc_id: "10001" } );
        //                 $.post("/document/putDocumentComment", {
        //                     title: "",
        //                     message: "",
        //                     status: doc_status,
        //                     user_id: user_id,
        //                     document_id: doc_id
        //                 });
        //             }
        //             var d = $('.d-' + doc_id);
        //             if (d.attr('class').indexOf('bg-') === -1) { d.addClass("bg-" + doc_status); }
        //             else { $('.bg-approved, .bg-pending').toggleClass('bg-approved bg-pending'); }
        //             d.toggleClass("b-approved b-pending");
        //             $('[data-key="' + doc_id + '"]').attr("data-doc_status", doc_status);
        //             d.find('.kv-file-status').children().toggleClass("approved fa-thumbs-up pending fa-thumbs-down");
        //             //find('i').toggleClass("fa-thumbs-up fa-thumbs-down");
        //             //.children().toggleClass("approved pending").data("doc_status", doc_status);
        //         }
        //     });
        // }
    }).mouseenter(function () {
        $(this).find("i").toggleClass("fa-thumbs-up fa-thumbs-down").toggleClass("approved pending");
    }).mouseleave(function () {
        $(this).find("i").toggleClass("fa-thumbs-up fa-thumbs-down").toggleClass("approved pending");
    });

    // Button Comment
    $('button.kv-file-comment').click(function (ev) {
        var doc_status = $(this).data("doc_status");
        var doc_id = $(this).data("doc_id");
        //$('#document-upload, #document-comment').toggle();
        ev.preventDefault();
        // var target = $(this).attr("href");
        // var target = "document/comment/" + doc_id + "/" + doc_status;
        var url = "document/comment/" + doc_id + "/view";

        // load the url and show modal on success
        preloader(url);
    });

    // Button Edit
    $('button.kv-file-edit').click(function (ev) {
        var doc_status = $(this).data("doc_status");
        var doc_id = $(this).data("doc_id");
        //$('#document-upload, #document-comment').toggle();
        ev.preventDefault();
        // var target = $(this).attr("href");
        // var target = "document/comment/" + doc_id + "/" + doc_status;
        var url = "document/request/" + shipment_id + "/" + doc_id;

        // load the url and show modal on success
        preloader(url);
    });

    // Validate Form
    $('form#form-modal').validate();

    // Submit Form
    $('button#submit').click(function (e) {
        var form = $('form#form-modal');
        var url = form.attr('action');

        if (form.valid()) {
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                beforeSend: function () {
                    form.empty().html('<center id="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>');
                },
                success: function (result) {
                    if (result == 'true') {
                        form.empty().html('Submitted Succesfully!');
                    } else {
                        form.empty().html(result);
                    }
                }
            });
        }

        // form.validate({
        //     success: "valid",
        //     showErrors: function(errorMap, errorList) {
        //         $("#summary").html("Your form contains "
        //           + this.numberOfInvalids()
        //           + " errors, see details below.");
        //         this.defaultShowErrors();
        //       },
        //     submitHandler: function() { 
        //         $.ajax({
        //             type: "POST",
        //             url: url,
        //             data: form.serialize(),
        //             beforeSend: function(){
        //                 form.empty().html('<center id="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>');  
        //             },
        //             success: function(result) {
        //                 if(result == 'false') {
        //                     form.empty().html('Submitted Succesful!');
        //                 } else {
        //                     form.empty().html(result);
        //                 }
        //             }
        //         });
        //     }

        // });

    });

    // Back Button
    $('button#go_back').click(function (e) {
        var url = "/shipment/document/" + shipment_id + "/" + document_type;
        preloader(url);
    });

    // Custom: Add checkbox in each file - preview - frame
    $('.file-preview-thumbnails > .file-preview-frame').each(function () {
        //var id = this.className.match(/d-\d+/);
        // $(this).append('<div class="checkbox">' +
        //     '<input class="form-check-input" type="checkbox" value="' + id + '">' +
        //     '</div>');
        $(this).hover(function () {
            $(this).children('.checkbox').toggle();
        });

    });

    $('#select-all').click(function() {
        var checked = false;
        if(this.checked) {
            checked = true;
        } else {
            checked = false;
        }

        $('.checkbox').find('input').each(function(){
            // $(this).prop('checked', checked);
            $(this).attr('checked', checked);
            $(this).parent().toggleClass('selected', checked);
        });
    });

    // Custom: function in each file-preview-frame
    $('.file-preview-thumbnails > .file-preview-frame > .checkbox').each(function () {
        $(this).on('change', function () {
            $(this).toggleClass('selected');
        });
    });

    $('#bulk-action').on('change', function () {
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        var textSelected = optionSelected.text();
        var groupSelected = optionSelected.parent().data('option');

        // console.log(optionSelected);
        // console.log(optionSelected.parent().data('option'));
        // console.log(valueSelected);
        // console.log(textSelected);

        var data = [];

        $('div[class*="selected"] > input').each(function () {
            data.push($(this).val().replace('d-', ''));
        });


        if (data.length === 0) {
            Swal.fire(
                'No file was selected!',
                'Please select a file(s) by ticking the checkbox.',
                'error'
            );
            $('#bulk-action').prop('selectedIndex', 0);
        } else {
            Swal.fire({
                title: "Are you sure?",
                html: "This bulk action will make all selected document <b>" + valueSelected + "</b>. <br>Do you still want to continue?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Yes, continue!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.post("/document/updateDocumentBulk", { group: groupSelected, value: valueSelected, data: data })
                        .done(function () {
                            if (valueSelected !== 'deleted') {
                                Swal.fire({
                                    title: 'It is better to leave a comment',
                                    text: 'Do you want to leave a comment?',
                                    icon: 'info',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonText: `Leave comment`,
                                    denyButtonText: `No`,
                                }).then((result) => {
                                    /* Read more about isConfirmed, isDenied below */
                                    if (result.isConfirmed) {
                                        Swal.fire('Status changed!', 'You will be redirected to comment form.', 'success');
                                        var url = "/document/comment/" + data + "/" + valueSelected;

                                        // load the url and show modal on success
                                        preloader(url);
                                    } else if (result.isDenied) {
                                        Swal.fire('Status changed!', 'Save but no comment made.', 'info')
                                        $.post("/document/putDocumentCommentBulk", {
                                            title: "",
                                            message: "",
                                            status: valueSelected,
                                            user_id: user_id,
                                            document_id: data
                                        });
                                    } else {
                                        Swal.fire('Status changed!', 'Save but no comment made.', 'info')
                                        $.post("/document/putDocumentCommentBulk", {
                                            title: "",
                                            message: "",
                                            status: valueSelected,
                                            user_id: user_id,
                                            document_id: data
                                        });
                                    }
                                });
                            }
                            // update uploader document
                            data.forEach(function (entry) {
                                var d = $('.d-' + entry);
                                if (groupSelected === 'status') {
                                    if (d.attr('class').indexOf('bg-') === -1) { d.addClass("bg-" + valueSelected); }
                                    else { $('.bg-approved, .bg-pending').toggleClass('bg-approved bg-pending'); }
                                    d.toggleClass("b-approved b-pending");
                                    $('[data-key="' + entry + '"]').attr("data-doc_status", valueSelected);
                                    d.find('.kv-file-status').children().toggleClass("approved fa-thumbs-up pending fa-thumbs-down");
                                    d.find('.file-footer-caption > #status').toggleClass("approved pending").text(valueSelected);
                                } else {
                                    switch (valueSelected) {
                                        case 'deleted':
                                            d.fadeOut(3000, function () { $(this).remove(); });
                                            break;
                                        case 'push':
                                            d.find('.kv-file-push').remove();
                                            break;
                                        default:
                                            console.log('Error: value = ' + valueSelected);
                                            break;
                                    }

                                }
                            });
                            $('#bulk-action').prop('selectedIndex', 0);
                            $('#select-all').prop('checked', false);
                            var url = "shipment/document/" + shipment_id + "/" + document_type;
                            preloader(url);
                        }).fail(function (response) {
                            console.log('Error: ' + response.responseText);
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Update successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    Swal.fire(
                        'Cancelled',
                        'No change was made',
                        'error'
                    );
                    $('#bulk-action').prop('selectedIndex', 0);
                }
            });
        }
    });


    // Other functions
    function file_ext(filename) {
        return typeof filename != "undefined" ? filename.substring(filename.lastIndexOf(".") + 1, filename.length).toLowerCase() : false;
    }

    // Show loader
    function preloader(url) {

        $("#myModal .modal-body").append(loader);

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

    // Truncate String
    function truncate(str, n) {
        return (str.length > n) ? str.substr(0, n - 1) + '&hellip;' : str;
    };

});

$(function () {

    var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
        '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';

    var formatName = function (item) {
        return $.trim((item.first_name || '') + ' ' + (item.last_name || ''));
    };

    $('#recipient').selectize({
        persist: false,
        maxItems: null,
        valueField: 'email',
        labelField: 'name',
        searchField: ['first_name', 'last_name', 'email'],
        sortField: [
            { field: 'first_name', direction: 'asc' },
            { field: 'last_name', direction: 'asc' }
        ],
        options: options,
        render: {
            item: function (item, escape) {
                var name = formatName(item);
                return '<div>' +
                    (name ? '<span class="name">' + escape(name) + '</span>' : '') +
                    (item.email ? '<span class="email">' + escape(item.email) + '</span>' : '') +
                    '</div>';
            },
            option: function (item, escape) {
                var name = formatName(item);
                var label = name || item.email;
                var caption = name ? item.email : null;
                return '<div>' +
                    '<span class="label">' + escape(label) + '</span>' +
                    (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
                    '</div>';
            }
        },
        createFilter: function (input) {
            var regexpA = new RegExp('^' + REGEX_EMAIL + '$', 'i');
            var regexpB = new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i');
            return regexpA.test(input) || regexpB.test(input);
        },
        create: function (input) {
            if ((new RegExp('^' + REGEX_EMAIL + '$', 'i')).test(input)) {
                return { email: input };
            }
            var match = input.match(new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i'));
            if (match) {
                var name = $.trim(match[1]);
                var pos_space = name.indexOf(' ');
                var first_name = name.substring(0, pos_space);
                var last_name = name.substring(pos_space + 1);

                return {
                    email: match[2],
                    first_name: first_name,
                    last_name: last_name
                };
            }
            alert('Invalid email address.');
            return false;
        }
    });
});