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
    console.log(typeof oval);
    if(typeof oval === 'string'){
      var inval = oval.split(",");
      htmlinvoice+=`<tr>
        <td><b>Invoice: </b> ${inval[0]} </td>
        <td><b>Match Report: </b> ${inval[1]} </td>
        <td><b>Response: </b> ${inval[2]} </td>
        <td><button type="button" id="${process_ID}_view"  data-pid="${process_ID}" class="btn btn-block btn-outline-primary viewdoc" data-toggle="modal" data-target="#modal-lg-prev">Preview document</button></td>
        <td><button  type="button" id="${process_ID}_delete"class="btn btn-block btn-outline-danger">Send to CargoWise</button></td>
        <td><b>Status: </b> ${inval[3]} </td>
        <td> ${inval[4]} </td>
        <td><b>CW Respons Status: </b> ${inval[5]} </td>
      </tr>`;
    }
    
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
  "info":     false,
  "ordering": false,
} );
$.fn.dataTable.ext.errMode = 'none';
$(document).ready(function() {
  
    var table = $('#example').DataTable( {
        "ajax": '/apinvoice/invoiceSuccess',
        "ordering": true,
        "bPaginate":true,
        "sPaginationType":"full_numbers",
        "iDisplayLength": 8,
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
        "order": [[1, 'desc']],
        
    } );

    //on click filter complete and all 
    $("#completedque").on('click',function(){
      table.ajax.reload; 
    });
    
    $("#totalque").on('click',function(){
      table.ajax.reload;
    });
    
    $(document).on('click',".viewdoc",function(){
      var pid = $(this).attr('data-pid');
      $("#embeded").append(loader);
      $('#loader-wrapper').addClass('loaderstyle');
      $("#embeded embed").attr('src','');
      
      $.ajax({
        url: document.location.origin+"/apinvoice/reprocessMatchReport/",
        type: "POST",
        data:{prim_ref:pid},
        success:function(data)
        {
         console.log(data);
        }
      });

      var headertable = $('#headerTable').DataTable( {
        "processing": false,
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
          {
            "data": null,
            "className": "dt-center editor-edit",
            "defaultContent": '<i class="fas fa-pencil-alt"></i>',
            "orderable": false
          },
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
        ]
      });
      $("#parsedTable_wrapper").attr("data-prim",pid);
      
      // $.ajax({
      //   url: document.location.origin+"/apinvoice/headerData/",
      //   type: "POST",
      //   data:{prim_ref:pid},
      //   success:function(data)
      //   {
      //     console.log(data);
      //     headertable.ajax.reload; 
      //   }
      // });

      
      //need to change this two ajax if have temporary because need to present
      // $.ajax({
      //   url: document.location.origin+"/apinvoice/parsedData/",
      //   type: "POST",
      //   data:{prim_ref:pid},
      //   success:function(data)
      //   {
      //     console.log(data);
      //     parsedTable.ajax.reload; 
      //   }
      // });

      /**
       * showing pdf preview and chargeline contents below pdf.
       */
       $('[data-toggle="tooltip"]').tooltip();

      $.ajax({
        url: document.location.origin+"/apinvoice/preview/",
        type: "POST",
        data:{prim_ref:pid},
        success:function(data)
        {
          $('#loader-wrapper').remove();
         var d = JSON.parse(data);
         var jreport = JSON.parse(d[0].match_report);
         console.log(d);
         if(checkpath(d[0].filename) === 'valid'){
          $("#embeded embed").attr('src',d[0].filename);
         }else{
          $("#embeded embed").attr('src',d[0].filepath);
         }
          //$("#embeded embed").attr('src',d[0].filename);
          
          var html='';
          var totolTipText,cwmbl,pdfmbl ='';
          try{
            if(typeof(jreport.HubJSONOutput.CargoWiseMatchedData.CWHeader) !== 'undefined'){
              $(".jobnum").text(jreport.HubJSONOutput.CargoWiseMatchedData.CWHeader.JobNumber);
              cwmbl='';
            }
  
            if(typeof(jreport.ParsedPDFData.ParsedPDFHeader.JobNumber) !== 'undefined'){
              $(".jobnum").text(jreport.ParsedPDFData.ParsedPDFHeader.JobNumber);
              pdfmbl='';
            }
          }catch(x){
            console.log("error");
          }

          totolTipText=`<span>CW MBL# ${cwmbl}</span></br>
                        <span>PDF MBL# ${pdfmbl}</span>`;

          $('.jobtooltip').attr('data-original-title',totolTipText);

          $.each(jreport.HubJSONOutput.MatchReport,function(okey,oval){
            var classkey = '';
            var contentText = '';
            if(Object.keys(oval).length !== 0){
              if(okey==='Errors'){
                classkey ="danger";
                contentText = oval;
              }else if(okey ==='Information'){
                classkey = "info_1"
                contentText = oval.InformationDetail.join("<br>");
              }else if(okey ==='Warnings'){
                classkey = "warning";
                contentText = oval.WarningsDetail;
              }else{
                classkey = "success"
                contentText = oval;
              }
            }
            

            html+=`<div id="cusdiv" class="${classkey}">
                    <p><strong>${okey}: </strong><br>
                    ${contentText}
                    </p>
                  </div>`;
            $("#infor-boxes").html(html);
            //console.log(okey); 
          });
          
        }
      });

    });

    $(".jobtooltip").on('click',function(){
      window.location.href=document.location.origin+"/shipment";
    });
    
    //saveing to archive
    $(document).on('click','.toarchive',function(){
      var processid = $(this).attr('data-pd');
      
      $.ajax({
        "url":  document.location.origin+"/apinvoice/saveToArchive",
        "type": "POST",
        "data":{process_id:processid},
        success:function(res){
          console.log(res);
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


    //check if file path is not 404 or exists
    function checkpath(url){
      var ret = '';
      $.ajax({
        "url":  document.location.origin+"/apinvoice/checkSite",
        "type": "POST",
        "data":{url:url},
        success:function(res){
          ret = res;
        }
      });
      return ret;
    }
    
  /*
     * BAR CHART
     * ---------
     */
    console.log(chartdata);
   // month1 = month1.toLowerCase();
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var chartNumbers = [];
    var tickMonth = [];
   // month1 = months.indexOf(month1);

    $.each(chartdata,function(okey,oval){
      var tickdate = oval.DateField.split("-");
      var formatdate = new Date(oval.DateField);
      var month = months[formatdate.getMonth()];
      console.log(tickdate[2]);
      tickMonth.push([okey,month+' - '+tickdate[2]]);
      chartNumbers.push([okey,parseInt(oval.countdate)]);
    });
    
    var bar_data = {
      data : chartNumbers,
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
        ticks: tickMonth
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
  
  //var parsedData = [];
  $(document).on("click","tr .editor-edit",function(){
    var indexTD = $(this).parent().find("td").attr('data-index');
    var prim_ref = $("#parsedTable_wrapper").attr("data-prim");
    var url = "/apinvoice/edit"
    $("#edit-ap .modal-body").load(url,{data:parsedData,index:indexTD, apinvoice: apinvoice,prim_ref:prim_ref},
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

  $('#edit-ap').on('hidden.bs.modal', function (e) {
    $('#parsedTable').DataTable().ajax.reload();
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
        xhr: function() {
          var xhr = new window.XMLHttpRequest();
          // Upload progress
          xhr.upload.addEventListener("progress", function(evt){
            
            if (evt.lengthComputable) {
              let progress = Math.round(evt.loaded * 100 / evt.total);
              for (let i = 0; i <= 100; i++) {
                setTimeout(function(){
                  $(".progress-bar").css("width", i + "%").text(i+'%');
                  $(".progress-bar").attr('aria-valuenow',i);
                },450);
              }
              //$(".progress-bar").css("width", progress + "%").text(progress+'%');
              //$(".progress-bar").attr('aria-valuenow',progress);
            
            }
        }, false);
        return xhr;
      },
      url: document.location.origin+"/apinvoice/uploadAndInsert/",
      contentType:false,
      cache:false,
      processData:false,
      type: "POST",
      data:form_data,
      beforeSend: function(){
        $(".file-preview").append(progressbar);
        //$("#modalloader").modal("show");
      },
      success:function(data)
      {
       // $("#modalloader").modal("hide");
        table.ajax.url( '/apinvoice/invoicesData' ).load();
        
         //call Compare api after upload
         $('.progress').remove();
         Swal.fire(
          "",
          "Your file was uploaded!",
          );
          Swal.fire({  
            title: 'Your file was uploaded!',  
            confirmButtonText: `OK`,  
          }).then((result) => {  
            /* Read more about isConfirmed, isDenied below */  
              if (result.isConfirmed) {    
                $('html, body').animate({
                  scrollTop: $("#example").offset().top
                });
              } 
          });
          //return 
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
      table.ajax.url( '/apinvoice/invoiceSuccess' ).load();
      var comdata = JSON.parse(data);
      console.log(comdata);
      //$("#totalque").val(comdata.que[0].que);
      $("#totalqueparent span").text(comdata.que[0].que);
      $("#completedqueparent span").text(comdata.completedCount[0].completed);
      $("#archivecountparent span").text(comdata.archive[0].archive);
      $("#allcountparent span").text(comdata.data.length);
      
    }
  });
}, 20000);

//load the all counts
$.ajax({
  url: document.location.origin+"/apinvoice/invoiceSuccess/",
  type: "POST",
  success:function(data)
  {
    var comdata = JSON.parse(data);
    console.log(comdata);
      $("#totalqueparent span").text(comdata.que[0].que);
      $("#completedqueparent span").text(comdata.completedCount[0].completed);
      $("#archivecountparent span").text(comdata.archive[0].archive);
      $("#allcountparent span").text(comdata.data.length);
  }
});

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
$('#modal-lg-prev').on('shown.bs.modal', function () {
  var looper = 0;
  var headerInterval = setInterval(function(){
    looper++;
    $('#headerTable').DataTable().ajax.reload();
    if(looper==10){
      clearInterval(headerInterval);
    }
  },10000);
})

$('#modal-lg-prev').on('hidden.bs.modal', function (e) {
  $('#headerTable').DataTable().clear();
  $('#parsedTable').DataTable().clear();
  $('#headerTable').dataTable().fnDestroy();
  $('#parsedTable').dataTable().fnDestroy();
  
});

$("#addtocw").on('click',function(){
  var prim_ref = $("#parsedTable_wrapper").attr("data-prim");

  $.ajax({
    url: document.location.origin+"/apinvoice/pushTOCW/",
    type: "POST",
    data:{"prim_ref":prim_ref},
    success:function(data)
    {
      console.log(data);
      Swal.fire(
        "",
        "Push Success!",
        );
    }
  });

});

});

/*collapse custom js*/
const sidebar = document.querySelector('.custom_sidebar');
const mainContent = document.querySelector('.main-content_custom');

function customside(){
  sidebar.classList.toggle('sidebar_small');
  mainContent.classList.toggle('main-content_large');
}
var progressbar = `<div class="progress">
<div class="progress-bar" role="progressbar"  aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
</div>`;
var loader = '<div id="loader-wrapper" class="d-flex justify-content-center">' +
  '<div class="spinner-border" role="status">' +
  '<span class="sr-only">Loading...</span>' +
  '</div>' +
  '</div>';
// Show loader
function preloader(url) {

  $("#myModal .modal-body").append(loader);

  // load the url and show modal on success
  $("#myModal .modal-body").load(url, function (response, status, xhr) {
    if (xhr.status == 200) {
      $('#loader-wrapper').remove();
      $("#myModal").modal("show");
    } else {
      alert("Error: " + xhr.status + ": " + xhr.statusText);
      $('#loader-wrapper').remove();
    }
  });

  $('button#request').toggle();
}


$(document).on('click','.cwresmodal',function(){
  var urlcw = "/apinvoice/cwresponse";
  var prim_ref = $(this).attr('data-pid');
  
  $("#cwresponsemodal .modal-body").append(loader);
  $("#cwresponsemodal .modal-body").load(urlcw,{prim_ref:prim_ref},
    function (response, status, xhr) {
        if (xhr.status == 200) {
            $('#loader-wrapper').remove();
            $("#cwresponsemodal").modal("show");
        } else {
            alert("Error: " + xhr.status + ": " + xhr.statusText);
            $('#loader-wrapper').remove();
        }
    });
});


