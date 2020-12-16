//JS script

// if (!window.location.origin) {
//   window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
// }

//show documents modal
$(document).on('click','.doc', function(e){
    e.preventDefault(); var type = "";
    if ($(this).data('type')) { type = "/" + $(this).data('type'); }
    $('#myModal').modal('show').find('.modal-body').load("/admin/document/" + $(this).data('id') + type);
});

//assign shipment to users
$(document).on("click",".assign",function(){
    var userId = $(this).data("userid");
    var shipId = $(this).data("shipid");
    $.ajax({
        url:"/admin/shipmentAssign",
        type:"POST",
        data:{"user_id":userId,"shipment_id":shipId},
        success: function(res){
          $(document).Toasts('create', {
            title: 'Success',
            body: 'Shipment was successfully assigned',
            autohide: true,
            close: false,
            class:'bg-success'
          })
        }
    });
});

//initialize data table
//$(document).ready(function() {
  var table = $('.table').DataTable({
    searching: false, 
    paging: false, 
    info: false,
    bFilter: true,
    //deferRender: true,
    sDom: 'lrtip',
    sEcho: true,
    processing: true,
    //stateSave: true,
    cache:true,
    destroy: true,
    "language": {
      processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
    serverSide: true,
    ajax: {
      type: "POST",
      url: document.location.origin + "/admin/shipmentSSR/" || window.location.href,
      data:function(d){
        d.shipment_id = $("input[name='shipment_id']").val();
        d.ETA = $("input[name='ETA']").val();
        d.client_name = $("input[name='client_name']").val();
        d.consignee = $("input[name='consignee']").val();
        d.consignor = $("input[name='consignor']").val();
        d.container = $("input[name='container']").val();
        d.origin = $("input[name='origin']").val();
        d.status = $("input[name='status']").val();
        d.post_trigger = $("input[name='post_trigger']").val();
      },
      error: function (xhr) {
          if (xhr.status === 401) {
              window.location.assign(window.location.href);
          }
          else if (xhr.status !== 0){
              alert("Ajax request failed.");
          }
      },
  },
    columnDefs: [
      { className: "stats", targets: [4,5,6,7,8] } 
    ]
  });
//});

//on search data table
$('#doc_search').on( 'keyup', function () {
  table.search( this.value ).draw();
});

//ETA time picker
$('input[name="ETA"]').daterangepicker({
  //timePicker: true,
  startDate: moment().startOf('hour'),
  endDate: moment().startOf('hour').add(32, 'hour'),
  autoUpdateInput: false,
  locale: {
    //format: 'M/DD hh:mm A'
    format: 'M/DD/YYYY'
  }
});

//on hide calendar set start date and end date
$('input[name="ETA"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
});

//for advance search.
$("#advance-search-btn").on("click",function(){
  $("input[name='post_trigger']").val("set");
  var data = $("#addvance-search-form").serializeArray();
  var query_data = {};
  var html="";
  $.each(data,function(key,value){
    var index = value.name;
    var val = value.value;
    query_data[index] = val;
  });
  table.clear();
  table.draw();
});
// $("#advance-search-btn").on("click",function(){
//   var data = $("#addvance-search-form").serializeArray();
//   var query_data = {};
//   var html="";
//   $.each(data,function(key,value){
//     var index = value.name;
//     var val = value.value;
//     query_data[index] = val;
//   });
  
//   $.ajax({
//     type: "POST",
//     url: "/admin/advanceSearch/",
//     ContentType: 'application/json',
//     data: query_data,
//     beforeSend: function(){
//       $("table tbody").html('<tr class="odd"><td valign="top" colspan="9" class="dataTables_empty">Loading...</td></tr>');
//     },
//     success: function(response){
//       console.log($.parseJSON(response));
//      if(response.length > 2){
//         $.each(JSON.parse(response),function(key,val){
//         var date_etd = new Date(val.etd);
//         var etd_date= (date_etd.getMonth() + 1) + '/' + date_etd.getDate() + '/' +  date_etd.getFullYear(); 
//         var date_eta = new Date(val.eta);
//         var eta_date= (date_eta.getMonth() + 1) + '/' + date_eta.getDate() + '/' +  date_eta.getFullYear(); 
//         html += `<tr>
//                   <td>${val.ex_shipment_num}</td>
//                   <td>${(val.console_id != "" ? val.console_id : "No Console ID") }</td>
//                   <td>${eta_date}</td>
//                   <td>${etd_date}</td>
//                   <td><span class="doc badge badge-success" data-type="HBL" data-id="${val.shipment_num}">Approved</span></td>
//                   <td><span class="doc badge badge-success" data-type="CIV" data-id="${val.shipment_num}">Approved</span></td>
//                   <td><span class="doc badge badge-warning" data-type="PKL" data-id="${val.shipment_num}">Pending</span></td>
//                   <td><span class="doc badge badge-success" data-type="PKD" data-id="${val.shipment_num}">Approved</span></td>
//                   <td><span class="doc badge badge-success" data-id="${val.shipment_num}">Approved</span></td>
//                   <td>${typeof(val.comment)!="undefined" ? val.comment : "<em>No comment</em>"}</td>
//                 </tr>`;
//       });
//      }else{
//        html ='<tr class="odd"><td valign="top" colspan="9" class="dataTables_empty">No matching records found</td></tr>';
//      }
//      $("table tbody").html(html);
//     }
//   });
// });

// Toggle document stats view 
$(document).on('mouseenter mouseleave','.stats',function() {
  $( this ).children().toggle( );
});

// Append loading and redraw datatable
$('#myModal').on('hidden.bs.modal', function (e){ 
  $('#myModal .modal-body').empty().append('Loading...');
  table.draw();
});