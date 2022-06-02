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