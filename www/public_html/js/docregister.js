  /*
     * BAR CHART
     * ---------
     */
    // var bar_data = {
    //   data : [[1,96], [2,83], [3,89], [4,75], [5,43], [6,62], [7,42]],
    //   bars: { show: true }
    // }
    // $.plot('#shipcount-chart', [bar_data], {
    //   grid  : {
    //     borderWidth: 1,
    //     borderColor: '#f3f3f3',
    //     tickColor  : '#f3f3f3'
    //   },
    //   series: {
    //      bars: {
    //       show: true, barWidth: 0.3, align: 'center',
    //     },
    //   },
    //   colors: ['#3c8dbc'],
    //   xaxis : {
    //     ticks: [[1,'JAN-19'], [2,'JAN-20'], [3,'JAN-21'], [4,'JAN-22'], [5,'JAN-23'], [6,'JAN-24'], [7,'JAN-25']]
    //   }
    // })
    /* END BAR CHART */
    var progressbar = `<div class="progress">
    <div class="progress-bar" role="progressbar"  aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>`;
    var loader = '<div id="loader-wrapper" class="d-flex justify-content-center">' +
  '<div class="spinner-border" role="status">' +
  '<span class="sr-only">Loading...</span>' +
  '</div>' +
  '</div>';
$(document).ready(function(){
  $(document).on('click','.pdfbtn',function(){
    var idembed = $(this).attr('data-embeded');
    
    $('embed').addClass('d-none');
    $('#'+idembed).removeClass('d-none');
  });

    var table = $('#docregister').DataTable( {
      "ajax": '/docregister/allDocs',
      "processing": true,
      "ordering": true,
      "bPaginate":true,
      "sPaginationType":"full_numbers",
      "iDisplayLength": 8,
      "oLanguage": {
        "sEmptyTable":     "Empty Data Please Upload Files."
      },
      "columns": [
          {
              "className":      'dt-control',
              "orderable":      false,
              "data":           null,
              "defaultContent": ''
          },
          { "data": "Process ID" },
          { "data": "File Name" },
          { "data": "Doc Number" },
          { "data": "Date Uploaded" },
          { "data": "Uploaded By" },
          { "data": "Action" },
          { "data": "Status" }
      ],
      "order": [[1, 'desc']],
      
  } );


  // Add event listener for opening and closing details
  $('#docregister tbody').on('click', 'td.dt-control', function () {
    
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

  $(document).on('click','.viewdoc',function(){
    var prim_ref = $(this).attr('data-prim_ref');
    console.log(prim_ref);
    var url = "/docregister/preview/" + prim_ref;
    preloader(url);
  });

  //upload file 
  $("#uploadoc, #upload-btn").on("click",function(){
    
    var file_data = $('#invoice').prop('files');   
    var form_data = new FormData(); 
    var data=[];
    var listOfProcessID = '';
    
    for(var i=0; i<file_data.length; i++){ 
      //formultiple file
      form_data.append('file[]', file_data[i]);
      //form_data.append('file', file_data[i]);
      data['form_data'] = form_data;
    }
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
            }
        }, false);
        return xhr;
      },
      url: document.location.origin+"/docregister/uploadAndInsert/",
      contentType:false,
      cache:false,
      processData:false,
      type: "POST",
      data:form_data,
      beforeSend: function(){
        //if(file_data.length == i){
          $(".file-preview").append(progressbar);
        //}
        
        //$("#modalloader").modal("show");
      },
      success:function(data)
      {
        $('#docregister').DataTable().ajax.reload();
        listOfProcessID = data;
        form_data.append('file[]', listOfProcessID)
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
        
          //form_data.append('pid[]',JSON.stringify(data));
          //return 
        $.ajax({
          url: document.location.origin+"/docregister/customUpload/",
          contentType: false,
              cache:false,
              processData:false,
          type: "POST",
          data:form_data,
          success:function(data)
          {
            var jsdata = data;
          }
        });
      }
    });
    
  });


  //post loaded data
  var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  var chartNumbers = [];
  var tickMonth = [];

  setTimeout(function(){
    $.ajax({
      url: document.location.origin+"/docregister/getcounts/",
      success:function(data)
      {
       var d = JSON.parse(data)
       console.log(d);
       console.log(d.new);
       $("#newcount span").text(d.new);
       $("#processcount span").text(d.processing);
       $("#completedcount span").text(d.completed);
       $("#failedcount span").text(d.failed);
       $("#archivecount span").text(d.archive);
      }
    });

    $.ajax({
      url: document.location.origin+"/docregister/chartdata/",
      success:function(data)
      {
        chartdata = JSON.parse(data);
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

        $("#shipcount-chart").height(500);
      }
    });

  },500);
});

function preloader(url) {
 
  $("#preview-doc .modal-body").append(loader);
  // load the url and show modal on success
  $("#preview-doc .modal-body").load(url, function (response, status, xhr) {
    if (xhr.status == 200) {
      $('#loader-wrapper').remove();
      $("#preview-doc").modal("show");
    } else {
      alert("Error: " + xhr.status + ": " + xhr.statusText);
      $('#loader-wrapper').remove();
    }
  });

  $('button#request').toggle();
}

function format ( d ) {
  console.log(d);
  var invoiceData = [];
  var htmlinvoice = '';
  var process_ID = d.pid; 
  $.each(d,function(okey,oval){
    console.log(oval);
    if(okey === "docs"){
      invoiceData =  oval;
    }
  });
 
  $.each(invoiceData.split(","),function(okey,oval){
    htmlinvoice+=`<td>${oval}</td>`;
  });
  
  if(invoiceData.length ==0){
    htmlinvoice = "<tr><td> No Results</tr></td>";
  }
    return `<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">${htmlinvoice}</table>`;
}


/*collapse custom js*/
const sidebar = document.querySelector('.custom_sidebar');
const mainContent = document.querySelector('.main-content_custom');

function customside(){
  sidebar.classList.toggle('sidebar_small');
  mainContent.classList.toggle('main-content_large');
}

$(document).on('click','#addtocw',function(){
  var prim_ref = $(this).attr("data-pid");

  $.ajax({
    url: document.location.origin+"/docregister/pushToCargowise/",
    type: "POST",
    data:{"process_id":prim_ref},
    success:function(data)
    {
      console.log(data.status);
      Swal.fire(
            "",
            "Push Success!",
            );
      // if(data.status ==='200'){
      //   Swal.fire(
      //     "",
      //     "Push Success!",
      //     );
      // }else{
      //   Swal.fire(
      //     "",
      //     "Error on Pushing!",
      //     );
      // }
      
    }
  });
});

$(document).on('click','.toarchive',function(){
  var processid = $(this).attr('data-pd');
  
  $.ajax({
    "url":  document.location.origin+"/docregister/toArchive/",
    "type": "POST",
    "data":{process_id:processid},
    success:function(res){
      console.log(res);
    }
  });
});

$('.clearall').on('click',function(){
  var processid = $(this).attr('data-pd');
  $.ajax({
    "url":  document.location.origin+"/docregister/archiveAll/",
    "type": "POST",
    "data":{process_id:processid},
    success:function(res){
      location.reload();
    }
  });
});

//show cw response
$(document).on('click','.cwresponse',function(){
    var prim_ref = $(this).attr('data-prim_ref');
    console.log(prim_ref);
    var url = "/docregister/cwresponse/" + prim_ref;
    $("#preview-cwresponse").append(loader);
    // load the url and show modal on success
    $("#preview-cwresponse .modal-body").load(url, function (response, status, xhr) {
      if (xhr.status == 200) {
        $('#loader-wrapper').remove();
        $("#preview-cwresponse").modal("show");
      } else {
        alert("Error: " + xhr.status + ": " + xhr.statusText);
        $('#loader-wrapper').remove();
      }
    });
});

