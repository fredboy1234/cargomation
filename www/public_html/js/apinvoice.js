// d  = data object of the row from json take note :)

function format ( d ) {
  console.log(d);
  var invoiceData = [];
  var htmlinvoice = '';
  var process_ID = d.pid; 
  $.each(d,function(okey,oval){
    if(okey === "invoices"){
      invoiceData =  oval;
    }
  });
 
  $.each(invoiceData,function(okey,oval){
    var inval = oval.split(",");
    htmlinvoice+=`<tr>
      <td><b>Invoice: </b> ${inval[0]} </td>
      <td><b>Match Report: </b> ${inval[1]} </td>
      <td><b>Response: </b> ${inval[2]} </td>
      <td><button type="button" id="${process_ID}_view"  data-pid="${process_ID}" class="btn btn-block btn-outline-primary viewdoc" data-toggle="modal" data-target="#modal-lg-prev">Preview document</button></td>
      <td><button  type="button" id="${process_ID}_delete"class="btn btn-block btn-outline-danger">Send to CargoWise</button></td>
      <td><b>Status: </b> ${inval[3]} </td>
    </tr>`; 
  });
  
  if(invoiceData.length ==0){
    htmlinvoice = "<tr><td> No Results</tr></td>";
  }
    return `<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">${htmlinvoice}</table>`;
}

$.extend( true, $.fn.dataTable.defaults, {
  "searching": true,
  "responsive": false,
  "paging":   false,
  "ordering": false,
  "info":     false
} );
$.fn.dataTable.ext.errMode = 'none';
$(document).ready(function() {
    var table = $('#example').DataTable( {
        "ajax": '/apinvoice/invoiceSuccess',
        "columns": [
            {
                "className":      'dt-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "Process ID" },
            { "data": "File Name" },
            { "data": "Job Number" },
            { "data": "Date Uploaded" },
            { "data": "Uploaded By" },
            { "data": "Action" },
            { "data": "Status" }
        ],
        "order": [[1, 'asc']]
    } );
    
    $(document).on('click',".viewdoc",function(){
      var pid = $(this).attr('data-pid');
      console.log('pid');
      console.log(pid);

      var headertable = $('#headerTable').DataTable( {
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "scrollY":        "250px",
        "fixedColumns": false,
        "ajax": {
            "url":  document.location.origin+"/apinvoice/headerData/",
            "type": "POST",
            "data":{prim_ref:pid},
        },
        "columns": [
          { "data": "ChargeCode" },
          { "data": "InvoiceNumber" },
          { "data": "InvoiceDate" },
          { "data": "Container" },
          { "data": "ExchangeRate" },
          { "data": "Creditor" },
          { "data": "InvoiceTo" },
          { "data": "SubTotal" },
          { "data": "GST" },
          { "data": "Discrepancy" }
        ]
      });

      // var headertable = $('#headerTable').DataTable({
      //   "ajax": "/apinvoice/headerData",
      //   "scrollX": true,
      //   "scrollY":        "250px",
      //   "fixedColumns": false,
      //   "columns": [
      //     { "data": "ChargeCode" },
      //     { "data": "InvoiceNumber" },
      //     { "data": "InvoiceDate" },
      //     { "data": "Container" },
      //     { "data": "ExchangeRate" },
      //     { "data": "Creditor" },
      //     { "data": "InvoiceTo" },
      //     { "data": "SubTotal" },
      //     { "data": "GST" },
      //     { "data": "Discrepancy" }
      //   ]
      // });

      var parsedTable = $('#parsedTable').DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
          "url":  document.location.origin+"/apinvoice/parsedData",
          "type": "POST",
          "data":{prim_ref:pid},
        },
        "scrollX": true,
        "scrollY":        "250px",
        "fixedColumns": false,
        "createdRow": function (row, data, rowIndex) {
          $.each($('td', row), function (colIndex) {
              $(this).attr('data-index',rowIndex);
          });
        },
        "columns": [
          { "data": "ChargeCode" },
          { "data": "InvoiceNumber" },
          { "data": "InvoiceDate" },
          { "data": "Container" },
          { "data": "ExchangeRate" },
          { "data": "Creditor" },
          { "data": "InvoiceTo" },
          { "data": "SubTotal" },
          { "data": "GST" },
          { "data": "Discrepancy" },
          {
            "data": null,
            "className": "dt-center editor-edit",
            "defaultContent": '<i class="fas fa-pencil-alt"></i>',
            "orderable": false
          },
        ]
      });

      $.ajax({
        url: document.location.origin+"/apinvoice/headerData/",
        type: "POST",
        data:{prim_ref:pid},
        success:function(data)
        {
          console.log(data);
          headertable.ajax.reload; 
        }
      });

      
      //need to change this two ajax if have temporary because need to present
      $.ajax({
        url: document.location.origin+"/apinvoice/parsedData/",
        type: "POST",
        data:{prim_ref:pid},
        success:function(data)
        {
          console.log(data);
          parsedTable.ajax.reload; 
        }
      });
    });
    

    
     
    // Add event listener for opening and closing details
    $('#example tbody').on('click', 'td.dt-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    });



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

function pushtocw(inv){
Swal.fire({
  title: 'Push data: ' + inv + ' ?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Push to CW'
}).then((result) => {
  if (result.isConfirmed) {
    Swal.fire(
      'Success!',
      'Your data has been pushed.',
      'success'
    )
  }
})}

var editor;
$(document).ready(function(){
  var url = "/apinvoice/edit";
  //var parsedData = [];
  $(document).on("click","tr .editor-edit",function(){
    var indexTD = $(this).parent().find("td").attr('data-index');
    $("#edit-ap .modal-body").load(url,{data:parsedData,index:indexTD, apinvoice: apinvoice},
      function (response, status, xhr) {
          if (xhr.status == 200) {
              $('#loader-wrapper').remove();
              $("#edit-ap").modal("show");
          } else {
              alert("Error: " + xhr.status + ": " + xhr.statusText);
              $('#loader-wrapper').remove();
          }
      });
  });

  // Bootstrap File Input
  $(".fileinput-upload, #upload-btn").on("click",function(){
    
    var file_data = $('#invoice').prop('files')[0];   
    var form_data = new FormData(); 
    var data=[];
    form_data.append('file', file_data);
    data['user_id'] = user_id;
    data['form_data'] = form_data;
   
    //auto insert after upload separated for some reason
    $.ajax({
      url: document.location.origin+"/apinvoice/uploadAndInsert/",
      contentType:false,
          cache:false,
          processData:false,
      type: "POST",
      data:form_data,
      success:function(data)
      {
        //table.ajax.url( '/apinvoice/invoicesData' ).load();
         //call Compare api after upload
         Swal.fire(
          "",
          "Your Was File Uploaded!",
          );
        $.ajax({
          url: document.location.origin+"/apinvoice/customUpload/",
          contentType:false,
              cache:false,
              processData:false,
          type: "POST",
          data:form_data,
          success:function(data)
          {
            
          }
        });
      }
    });

  });
  var $el1 = $("#invoice");
  $el1.fileinput({
      theme: 'fas',
      uploadUrl: document.location.origin + "/apinvoice/upload" + param,
      //deleteUrl: document.location.origin + "/api/post/apinvoice/delete" + param,
      uploadAsync: false,

      minFileCount: 1,
      maxFileCount: 5,
      browseOnZoneClick: true,
  }).on('filepreupload', function (event, data, previewId, index, fileId) {
      alert('filepreupload');
      // var form = data.form, files = data.files, extra = data.extra,
      //     response = data.response, reader = data.reader;
      // console.log('File pre upload triggered', fileId);
  }).on('fileuploaded', function (event, data, id, index) {
      alert('THIS IS BATCH');

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
  })
});


setInterval(function () {
  $.ajax({
    url: document.location.origin+"/apinvoice/invoiceSuccess/",
    type: "POST",
    success:function(data)
    {
      //console.log(data);
      table.ajax.url( '/apinvoice/invoiceSuccess' ).load();
    }
  });
}, 20000);

// //on click priview documents get specific match report
// $(document).on('click','.viewdoc',function(){
//   var pid = $(this).attr('data-pid');
//   console.log('pid');
//   console.log(pid);
//   //need to change this two ajax if have temporary because need to Demo
//   $.ajax({
//     url: document.location.origin+"/apinvoice/headerData/",
//     type: "POST",
//     data:{prim_ref:pid},
//     success:function(data)
//     {
//       console.log(data);
//       headertable.ajax.reload; 
//     }
//   });
//   //need to change this two ajax if have temporary because need to present
//   $.ajax({
//     url: document.location.origin+"/apinvoice/parsedData/",
//     type: "POST",
//     data:{prim_ref:pid},
//     success:function(data)
//     {
//       console.log(data);
//       parsedTable.ajax.reload; 
//     }
//   });
// });

$('#modal-lg-prev').on('hidden.bs.modal', function (e) {
  $('#headerTable').DataTable().clear();
  $('#parsedTable').DataTable().clear();
  $('#headerTable').dataTable().fnDestroy();
  $('#parsedTable').dataTable().fnDestroy();
})

});

/*collapse custom js*/
const sidebar = document.querySelector('.custom_sidebar');
const mainContent = document.querySelector('.main-content_custom');

function customside(){
  sidebar.classList.toggle('sidebar_small');
  mainContent.classList.toggle('main-content_large');
}