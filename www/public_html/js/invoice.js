// Bootstrap File Input
$("#invoice").fileinput({
    theme: 'fas',
    uploadUrl: "/invoice/upload/" + user_id,
    deleteUrl: "/invoice/delete/" + user_id,
    enableResumableUpload: true,
    resumableUploadOptions: {
        // uncomment below if you wish to test the file for previous partial uploaded chunks
        // to the server and resume uploads from that point afterwards
        // testUrl: "http://localhost/test-upload.php"
    },
    uploadExtraData: {
        'uploadToken': token, // for access control / security 
    },
    maxFileCount: 20,
    // allowedFileTypes: ['docx'],    // allow only images
    showCancel: true,
    initialPreviewAsData: true,
    overwriteInitial: false,
    // initialPreview: [],          // if you have previously uploaded preview files
    // initialPreviewConfig: [],    // if you have previously uploaded preview files
}).on('fileuploaded', function(event, previewId, index, fileId) {
    console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
}).on('fileuploaderror', function(event, data, msg) {
    console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
}).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
    console.log('File Batch Uploaded', preview, config, tags, extraData);
});
