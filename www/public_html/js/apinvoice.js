// d  = data object of the row from json take note :)

function format ( d ) {
  let output1 = d.Parsed01 + '';
  let output2 = d.Parsed02 + '';
  let output3 = d.Parsed03 + '';
  let output4 = d.Parsed04 + '';
  let output5 = d.Parsed05 + '';

  const parsed1 = output1.split(",");
  const parsed2 = output2.split(",");
  const parsed3 = output3.split(",");
  const parsed4 = output4.split(",");
  const parsed5 = output5.split(",");


    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td><b>Invoice:  </b> '+ parsed1[0] + '</td>'+
            '<td><b>Match Report:  </b> '+ parsed1[1] + '</td>'+
            '<td><b>Response:  </b> '+ parsed1[2] + '</td>'+
            '<td><button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modal-lg-prev">Preview document</button></td>'+
            '<td><button onclick="pushtocw(\'' + parsed1[0]  + '\')" type="button" class="btn btn-block btn-outline-danger">Send to CargoWise</button></td>'+
            '<td><b>Status:  </b> '+ parsed1[3] + '</td>'+
        '</tr>'+
        '<tr>'+
            '<td><b>Invoice:</b> '+ parsed2[0] + '</td>'+
            '<td><b>Match Report:</b> '+ parsed2[1] + '</td>'+
            '<td><b>Response:</b> '+ parsed2[2] + '</td>'+
            '<td><button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modal-lg-prev">Preview document</button></td>'+
            '<td><button onclick="pushtocw(\'' + parsed2[0]  + '\')" type="button" class="btn btn-block btn-outline-danger">Send to CargoWise</button></td>'+
            '<td><b>Status:  </b> '+ parsed2[3] + '</td>'+
        '</tr>'+
        '<tr>'+
            '<td><b>Invoice:</b> '+ parsed3[0] + '</td>'+
            '<td><b>Match Report:</b> '+ parsed3[1] + '</td>'+
            '<td><b>Response:</b> '+ parsed3[2] + '</td>'+
            '<td><button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modal-lg-prev">Preview document</button></td>'+
            '<td><button onclick="pushtocw(\'' + parsed3[0]  + '\')" type="button" class="btn btn-block btn-outline-danger">Send to CargoWise</button></td>'+
            '<td><b>Status:  </b> '+ parsed3[3] + '</td>'+
        '</tr>'+
        '<tr>'+
            '<td><b>Invoice:</b> '+ parsed4[0] + '</td>'+
            '<td><b>Match Report:</b> '+ parsed4[1] + '</td>'+
            '<td><b>Response:</b> '+ parsed4[2] + '</td>'+
            '<td><button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modal-lg-prev">Preview document</button></td>'+
            '<td><button onclick="pushtocw(\'' + parsed4[0]  + '\')" type="button" class="btn btn-block btn-outline-danger"">Send to CargoWise</button></td>'+
            '<td><b>Status:  </b> '+ parsed4[3] + '</td>'+
        '</tr>'+
        '<tr>'+
            '<td><b>Invoice:</b> '+ parsed5[0] + '</td>'+
            '<td><b>Match Report:</b> '+ parsed5[1] + '</td>'+
            '<td><b>Response:</b> '+ parsed5[2] + '</td>'+
            '<td><button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modal-lg-prev">Preview document</button></td>'+
            '<td><button onclick="pushtocw(\'' + parsed5[0]  + '\')" type="button" class="btn btn-block btn-outline-danger">Send to CargoWise</button></td>'+
            '<td><b>Status:  </b> '+ parsed5[3] + '</td>'+
        '</tr>'+
    '</table>';
}
 
$(document).ready(function() {
    var table = $('#example').DataTable( {
        "ajax": '../settings/apinvoiceparse.json',
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
    } );
} );


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
