//JS script

// if (!window.location.origin) {
//   window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
// }

//show documents modal
$(document).on('click','.doc', function(e){
  e.preventDefault(); var type = "";
  if ($(this).data('type')) { type = "/" + $(this).data('type'); }
  $('#myModal').modal('show').find('.modal-body').load("/shipment/document/" + $(this).data('id') + type);
});

//assign shipment to users
$(document).on("click",".assign",function(){
  var userId = $(this).data("userid");
  var shipId = $(this).data("shipid");
  $.ajax({
      url:"/shipment/shipmentAssign",
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

$(document).ready(function(){

  var tableColumnData = [];
  $.each(userData,function(okey,oval){
    tableColumnData.push({data:oval.index_name.replace(/\s+/g, '_').toLowerCase()});
  });
  
  var table = $('.table').DataTable({
    searching: true, 
    paging: false, 
    info: false,
    responsive: true,
    autoWidth: false,
    lengthChange: false,
    colReorder: true,
    // bSort: true,
    // ordering: true,
    // bFilter: true,
    // deferRender: true,
    // sDom: 'lrtip',
    // sEcho: true,
    // stateSave: true,
    // cache:true,
    // destroy: true,
    processing: true,
    language: {
      processing: '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>'},
    serverMethod: 'post',
    ajax: {
      url: document.location.origin + '/shipment/shipmentSSR/',
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
    },
    columns: tableColumnData,
    columnDefs: [
      { className: "stats", targets: [4,5,6,7,8] } 
    ]
  });


//on search data table
$('#doc_search').on( 'keyup', function () {
  table.search( this.value ).draw();
});


//ETA time picker
var start = moment().subtract(29, 'days');
var end = moment();

$('input[name="ETA"]').daterangepicker({
  //timePicker: true,
  startDate: moment().startOf('hour'),
  endDate: moment().startOf('hour').add(32, 'hour'),
  autoUpdateInput: false,
  ranges: {
    'Today': [moment(), moment()],
    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    'This Month': [moment().startOf('month'), moment().endOf('month')],
    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
 },
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
  var check_arr = [];
  $("input[name='post_trigger']").val("set");
  $("input:checkbox[name=stat]:checked").each(function(){
    check_arr.push($(this).val());
  });
  $("#status").val(check_arr);
 
  var data = $("#addvance-search-form").serializeArray();
  var query_data = {};
  console.log(query_data);
  var html="";
  $.each(data,function(key,value){
    var index = value.name;
    var val = value.value;
    query_data[index] = val;
  });
 
  table.ajax.reload();
  table.draw();
});

// Toggle document stats view 
$(document).on('mouseenter mouseleave','.stats',function() {
  $( this ).children().toggle( );
});

// Append loading and redraw datatable
$('#myModal').on('hidden.bs.modal', function (e){ 
  $('#myModal .modal-body').empty().append('Loading...');
  table.ajax.reload();
});

//For Settings
$('select[name="settings-dual"]').bootstrapDualListbox({
  nonSelectedListLabel: 'Non-selected Settings',
  selectedListLabel: 'Selected Settings',
  preserveSelectionOnMove: 'all',
  helperSelectNamePostfix:'_helper',
  sortByInputOrder: true,
  filterTextClear: ""
});

//chick on click button all 
var wasClicked = false;
$(document).on('click','.moveall, .removeall',function(e){    
  wasClicked = true;
});

var map = $('select[name="settings-dual"]').on("change",function(e){
  setTimeout(function(){
  var comp = $("select[name='settings-dual'] option:selected").map(function() {
          return this.value;
      }).get(),

  set1 = map.filter(function(i) {
      return comp.indexOf(i) < 0;
  }),
  set2 = comp.filter(function(i) {
      return map.indexOf(i) < 0;
  }),
  last = (set1.length ? set1 : set2)[0];

  map = comp;
  var Sdata = getSettings(map);
  
  $.ajax({
    url: document.location.origin + '/shipment/addUserSettings/',
    type: "POST",
    dataType:"json",
    data:{"settings":Sdata},
    success:function(res){
      // for checking only
      // console.log(res);
    }
  });
  
    if(wasClicked){
      if(map.length > 0){
        $.each(map,function(okey,oval){
          var column = table.column( oval );
          column.visible(column);
        });
      }else{
        var column = table.columns();
        column.visible( ! column.visible() );
      }
    
      wasClicked = false;
    }else{
      var column = table.column( last );
      column.visible( ! column.visible() );
    }
    console.log( "last -- "+last );
  },100);
  

}).find('option:selected').map(function() {return this.value}).get();

var check_arr = [];
function getSettings(map){
  var Sdata = [];
  var opt_value = [];
  var temp = [];
  $('#bootstrap-duallistbox-selected-list_settings-dual  option').each(function(){
    opt_value.push($(this).val());
    Sdata.push({index_name:$(this).data('text'),index_value:$(this).val(),index_check:true});
  }); 
  
  $('#bootstrap-duallistbox-nonselected-list_settings-dual  option').each(function(){
    opt_value.push($(this).val());
    Sdata.push({index_name:$(this).data('text'),index_value:$(this).val(),index_check:false});
  }); 

  if(check_arr.length != Sdata.length && check_arr.length != 0){
    $.each(check_arr,function(k,v){
      if($.inArray(v.index_value,opt_value) == -1){
        temp.push({index_name:v.index_name,index_value:v.index_value,index_check:v.index_check});
      }else{
        $.each(Sdata,function(okey,oval){
          if(oval.index_value == v.index_value){
            temp.push({index_name:oval.index_name,index_value:oval.index_value,index_check:oval.index_check});
          }
        });
      }
    });
    Sdata = [];
    Sdata = temp;
  }
  check_arr = Sdata;
  //console.log(Sdata);
  return Sdata;
}
var hide = getSettings();
$.each(hide,function($key,$val){
  if($val.index_check == false){
    var column = table.column( $val.index_value );
    column.visible( ! column.visible() );
  }
});

$('.moveall').text('Show All');
$('.removeall').text('Hide All');
//end of settings

  table.on('column-reorder',function(e, settings, details){
     
      $("select[name='settings-dual'] option").each(function(){
        if($(this).val() == details.from){
          $(this).val(details.to);
          $(this).attr('id',details.to);
        }else if($(this).val() == details.to){
          $(this).val(details.from);
          $(this).attr('id',details.from);
        }
      }); 
      $('#bootstrap-duallistbox-selected-list_settings-dual  option').each(function(){
        if($(this).val() == details.from){
          $(this).val(details.to);
          $(this).attr('id',details.to);
        }else if($(this).val() == details.to){
          $(this).val(details.from);
          $(this).attr('id',details.from);
        }
      }); 
      setTimeout(function(){
        var Sdata = getSettings();
        $.ajax({
          url: document.location.origin + '/shipment/addUserSettings/',
          type: "POST",
          dataType:"json",
          data:{"settings":Sdata},
          success:function(res){
            // for checking only
            // console.log(res);
          }
        });
      },100);
     
  });
  
});


    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
