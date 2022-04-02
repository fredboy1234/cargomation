  /*
     * BAR CHART
     * ---------
     */
    var bar_data = {
      data : [[1,96], [2,83], [3,89], [4,75], [5,43], [6,62], [7,42]],
      bars: { show: true }
    }
    $.plot('#shipcount-chart', [bar_data], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
         bars: {
          show: true, barWidth: 0.3, align: 'center',
        },
      },
      colors: ['#3c8dbc'],
      xaxis : {
        ticks: [[1,'JAN-19'], [2,'JAN-20'], [3,'JAN-21'], [4,'JAN-22'], [5,'JAN-23'], [6,'JAN-24'], [7,'JAN-25']]
      }
    })
    /* END BAR CHART */


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
