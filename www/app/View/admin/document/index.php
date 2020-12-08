<!DOCTYPE html>
<!-- release v5.1.4, copyright 2014 - 2020 Kartik Visweswaran -->
<!--suppress JSUnresolvedLibraryURL -->
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Krajee JQuery Plugins - &copy; Kartik</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
    <link href="/bower_components/bootstrap-fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">
    <link href="/bower_components/bootstrap-fileinput/themes/explorer-fas/theme.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="/bower_components/bootstrap-fileinput/js/plugins/piexif.js" type="text/javascript"></script>
    <script src="/bower_components/bootstrap-fileinput/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="/bower_components/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>
    <script src="/bower_components/bootstrap-fileinput/js/locales/fr.js" type="text/javascript"></script>
    <script src="/bower_components/bootstrap-fileinput/js/locales/es.js" type="text/javascript"></script>
    <script src="/bower_components/bootstrap-fileinput/themes/fas/theme.js" type="text/javascript"></script>
    <script src="/bower_components/bootstrap-fileinput/themes/explorer-fas/theme.js" type="text/javascript"></script>
    <style>
        /* new file uploader */
        .file-actions {
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        .file-footer-buttons {
            float: right;
        }
        .krajee-default .file-other-icon {
            line-height: 1.5 ;
        }
        .file-caption-name {
            display: inline-block;
            font-size: 13px;
            overflow-x: hidden;
            height: 20px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .approved {
            color: #28a745;
        }
        .pending {
            color: #dc3545;
        }
    </style>
</head>
<body>

<?php if(!empty($this->document)) {
    foreach ($this->document as $key => $file) {
        $initialPreviewData[] = ['caption' => $file->name,
                                   'width' => '200px',
                                    'type' => 'pdf',
                                   'extra' => ['status' => $file->status]];
        $initialPreview[] = $file->name;
        $initialPreviewThumbTags[] = ['{status}' => $file->status, 
                                          '{id}' => $file->document_id, 
                                        '{date}' => $file->saved_date,
                                      '{origin}' => $file->upload_src];
    }
} ?>
    <div>
        <h4>Shipment ID: <?= $this->shipment['shipment_id']; ?></h4>
    </div>
    <div class="file-loading">
        <input id="input" name="input[]" type="file" accept="application/*" multiple>
    </div>
    <div id="kv-error-1" style="margin-top:10px; display:none"></div>
    <div id="kv-success-1" class="alert alert-success" style="margin-top:10px; display:none"></div>
    
    <script>
    var btns = '<button type="button" class="kv-file-remove btn btn-sm btn-kv btn-default btn-outline-secondary {removeClass}" title="{removeTitle}"{dataUrl}{dataKey}><i class="fas fa-comment"></i></button>\n';
    $(document).ready(function() {
        var $el1 = $("#input");
        $el1.fileinput({
            theme: 'fas',
            allowedFileExtensions: ['docx', 'pdf', 'xls'],
            uploadUrl: "uploads.php",
            uploadAsync: true,
            deleteUrl: "delete.php",
        <?php if(!empty($this->document)): ?>
            initialPreview: <?php echo json_encode($initialPreview); ?>,
            initialPreviewAsData: true,
            initialPreviewFileType: 'pdf',
            initialPreviewConfig: <?php echo json_encode($initialPreviewData); ?>,
            initialPreviewThumbTags: <?php echo json_encode($initialPreviewThumbTags); ?>,
        <?php endif; ?>
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
            },
            layoutTemplates: {
                main1: '{preview}\n' +
                    '<div class="kv-upload-progress kv-hidden"></div><div class="clearfix"></div>\n' +
                    '<div class="input-group {class}">\n' +
                    '  {caption}\n' +
                    '  <div class="input-group-btn">\n' +
                    '    {remove}\n' +
                    '    {cancel}\n' +
                    '    {pause}\n' +
                    '    {upload}\n' +
                    '    {browse}\n' +
                    '  </div>\n' +
                    '</div>',
                main2: '{preview}\n<div class="kv-upload-progress hide"></div>\n{remove}\n{cancel}\n{upload}\n{browse}\n',
                preview: '<div class="file-preview {class}">\n' +
                    '    {close}\n' +
                    '    <div class="close fileinput-remove">×</div>\n' +
                    '    <div class="{dropClass}">\n' +
                    '    <div class="file-preview-thumbnails">\n' +
                    '    </div>\n' +
                    '    <div class="clearfix"></div>' +
                    '    <div class="file-preview-status text-center text-success"></div>\n' +
                    // '    <div class="kv-fileinput-error"></div>\n' +
                    '    </div>\n' +
                    '</div>',
                icon: '<span class="glyphicon glyphicon-file kv-caption-icon"></span>',
                caption: '<div class="file-caption form-control {class}" tabindex="500">\n' +
                    '  <span class="file-caption-icon"></span>\n' +
                    '  <input class="file-caption-name" onkeydown="return false;" onpaste="return false;">\n' +
                    '</div>',
                btnDefault: '<button type="{type}" tabindex="500" title="{title}" class="{css}"{status}>{icon}{label}</button>',
                btnLink: '<a href="{href}" tabindex="500" title="{title}" class="{css}"{status}>{icon}{label}</a>',
                btnBrowse: '<div tabindex="500" class="{css}"{status}>{icon}{label}</div>',
                modalMain: '<div id="kvFileinputModal" class="file-zoom-dialog modal fade" tabindex="-1" aria-labelledby="kvFileinputModalLabel"></div>',
                modal: '<div class="modal-dialog modal-lg{rtl}" role="document">\n' +
                    '  <div class="modal-content">\n' +
                    '    <div class="modal-header">\n' +
                    '      <div class="kv-zoom-actions pull-right">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
                    '      <h3 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h3>\n' +
                    '    </div>\n' +
                    '    <div class="modal-body">\n' +
                    '      <div class="floating-buttons"></div>\n' +
                    '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
                    '    </div>\n' +
                    '  </div>\n' +
                    '</div>\n',
                progress: '<div class="progress">\n' +
                    '    <div class="progress-bar progress-bar-success progress-bar-striped text-center" role="progressbar" aria-valuenow="{percent}" aria-valuemin="0" aria-valuemax="100" style="width:{percent}%;">\n' +
                    '        {status}\n' +
                    '     </div>\n' +
                    '</div>\n' +
                    '{stats}',
                stats: '<div class="text-info file-upload-stats">' +
                    '<span class="pending-time">{pendingTime}</span> ' +
                    '<span class="upload-speed">{uploadSpeed}</span>' +
                    '</div>',
                footer: '<div class="file-thumbnail-footer">\n' +
                    '    <div class="file-caption-name" style="width:{width}">{caption}</div>\n' +
                    '    {progress} {actions}\n' +
                    '</div>',
                actions: '<div class="file-actions">\n' +
                    '    <div class="file-footer-buttons">\n' +
                    '        {upload} {download} {delete} {zoom} {other}' +
                    '    </div>\n' +
                    '    {drag}\n' +
                    '    <div class="file-upload-indicator" title="{indicatorTitle}">{indicator}</div>\n' +
                    '    <div class="clearfix"></div>\n' +
                    '</div>',
                actionDelete: '<button type="button" class="kv-file-remove {removeClass}" title="{removeTitle}"{dataUrl}{dataKey}>{removeIcon}</button>\n',
                actionUpload: '<button type="button" class="kv-file-upload {uploadClass}" title="{uploadTitle}">{uploadIcon}</button>\n',
                actionDownload: '<button type="button" class="{downloadClass}" title="{downloadTitle}" data-url="{downloadUrl}" data-caption="{caption}">{downloadIcon}</button>\n',
                actionZoom: '<button type="button" class="kv-file-zoom {zoomClass}" title="{zoomTitle}">{zoomIcon}</button>',
                actionDrag: '<span class="file-drag-handle {dragClass}" title="{dragTitle}">{dragIcon}</span>'
            },
            otherActionButtons: '<button type="button" ' +
                                    'class="kv-file-comment btn btn-sm btn-kv btn-default btn-outline-secondary {removeClass}" ' + 
                                    'title="{removeTitle}"{dataUrl}{dataKey}>' +
                                        '<i class="fas fa-comment"></i>' +
                                '</button>\n' +
                                '<button type="button" ' +
                                    'class="kv-file-status btn btn-sm btn-kv btn-default btn-outline-secondary {removeClass}" ' + 
                                    'title="{removeTitle}"{dataUrl}{dataKey} ' +
                                    'data-doc_id="{id}" data-doc_status="{status}">' +
                                        '<i class="fas {status} fa-thumbs-down"></i>' +
                                '</button>\n',
        }).on('filebatchpreupload', function(event, data) {
            $('#kv-success-1').html('<h4>Upload Status</h4><ul></ul>').hide();
            var n = data.files.length, files = n > 1 ? n + ' files' : 'one file';
            if (!window.confirm("Are you sure you want to upload " + files + "?")) {
                return {
                    message: "Upload aborted!", // upload error message
                    data:{} // any other data to send that can be referred in `filecustomerror`
                };
            }
        }).on("filebatchselected", function(event, files) {
                $el1.fileinput("upload");
        }).on('fileuploaded', function(event, data, id, index) {
            var fname = data.files[index].name,
                out = '<li>' + 'Uploaded file # ' + (index + 1) + ' - '  +  fname + ' successfully.' + '</li>';
            $('#kv-success-1').append(out);
            $('#kv-success-1').fadeIn('slow');
            setTimeout(function(){
                $('#kv-success-1').fadeOut('slow');
                $('.kv-upload-progress').fadeOut('slow');

            }, 3000);
        }).on('fileuploaderror', function(event, data, msg) {
            console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
        }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
            console.log('File Batch Uploaded', preview, config, tags, extraData);
        }).on('filebatchuploadsuccess', function(event, data) {
            var out = '';
            $.each(data.files, function(key, file) {
                var fname = file.name;
                out = out + '<li>' + 'Uploaded file # ' + (key + 1) + ' - '  +  fname + ' successfully.' + '</li>';
            });
            $('#kv-success-2 ul').append(out);
            $('#kv-success-2').fadeIn('slow');
        });

        
    });

    function file_ext(filename) {
        return typeof filename != "undefined" ? filename.substring(filename.lastIndexOf(".")+1, filename.length).toLowerCase() : false;
    }
    </script>
    <?= $this->getJS(); ?>
</body>

</html>