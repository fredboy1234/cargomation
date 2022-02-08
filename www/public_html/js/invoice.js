    // Bootstrap File Input
    $("#invoice").fileinput({
        theme: 'fas',
        uploadUrl: "/invoice/uploadDocument/" + user_id,
        uploadAsync: false,
        minFileCount: 1,
        maxFileCount: 20,
        overwriteInitial: false,
        initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
    }).on('filesorted', function(e, params) {
        console.log('File sorted params', params);
    }).on('fileuploaded', function(e, params) {
        console.log('File uploaded params', params);
    });