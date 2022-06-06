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
    var progressbar = `<div class="progress">
    <div class="progress-bar" role="progressbar"  aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>`;
$(document).ready(function(){
    var table = $('#docregister').DataTable( {
      "ajax": '/docregister/allDocs',
      "processing": true,
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

  $(document).on('click','.custom',function(){
    var prim_ref = $(this).attr('data-prim_ref');
    console.log(prim_ref);
    var url = "/docregister/preview/" + prim_ref;
    preloader(url);
  });

  //upload file 
  $("#uploadoc, #upload-btn").on("click",function(){
  
    var file_data = $('#invoice').prop('files')[0];   
    var form_data = new FormData(); 
    var data=[];
    form_data.append('file', file_data);
   // data['user_id'] = user_id;
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
        $(".file-preview").append(progressbar);
        //$("#modalloader").modal("show");
      },
      success:function(data)
      {
        $('#docregister').DataTable().ajax.reload();
        
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
          url: document.location.origin+"/docregister/customUpload/",
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
});

function preloader(url) {
 var loader ='';
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

$('#myTab .nav-item').on('click',function(){
  var idembed = $(this).attr('data-embeded');
  $('embed').addClass('d-none');
  $('#'+idembed).removeClass('d-none');
});