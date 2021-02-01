//JS script

// if (!window.location.origin) {
//   window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
// }

var loader = '<div id="loader-wrapper" class="d-flex justify-content-center">' + 
                '<div class="spinner-border" role="status">' + 
                  '<span class="sr-only">Loading...</span>' + 
                '</div>' + 
              '</div>'; 

var query_string = window.location.search.substring(1);
var parsed_qs = parse_query_string(query_string);

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
  
  if(parsed_qs.calendar !='' && typeof parsed_qs.calendar !='undefined'){
    var c_date = new Date(parsed_qs.calendar);
    var curr_date = ("0" + (c_date.getDate() + 1)).slice(-2);
    var curr_month = ("0" + (c_date.getMonth() + 1)).slice(-2);
    var curr_year = c_date.getFullYear();
    calendar_end_date = curr_month+'/'+curr_date+'/'+curr_year;
    calendar_start_date = parsed_qs.calendar;
    $("input[name='ETA']").val(calendar_start_date+' - '+ calendar_end_date );
    $("input[name='post_trigger']").val("set");
    setTimeout(function(){
      $("#advance-search-btn").trigger('click');
    },1000);
   
  }

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
    colReorder:true,
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
      processing: '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>'
    },
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
              d.origin = $("input[name='origin']:checked").val();
              d.status = $("input[name='status']").val();
              d.transportmode = $("input[name='transportmode']:checked").val();
              d.post_trigger = $("input[name='post_trigger']").val();
            },
    },
    columns: tableColumnData,
    fnCreatedRow: function( nRow, aData, iDataIndex ) {
      $('td', nRow).eq(0).append(`${$(".parent-assign").html()}`);
      $(nRow).attr("ship-assign-id",aData['real_id_shipment']);
      $('.assign').attr('data-shipid',aData['real_id_shipment']);
    },
    columnDefs: [
      { className: "stats", targets: [4,5,6,7,8] } 
    ],
    initComplete: setColor
  });
  
  if(parsed_qs.doc !== '' && typeof parsed_qs.doc !='undefined' ) {
    table.search( parsed_qs.doc ).draw();
  } 

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
    'Last Week': [moment().subtract(6, 'days'), moment().subtract(13, 'days')],
    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    'Last 14 Days': [moment().subtract(13, 'days'), moment()],
    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
    'Last 2 Months': [moment().subtract(2, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
    'Last 3 Months': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
    'Last 6 Months': [moment().subtract(6, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
    'Last 12 Months': [moment().subtract(12, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
    'This Month': [moment().startOf('month'), moment().endOf('month')],
    'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
    'Next Week': [moment().add(6, 'days'), moment().add(12, 'days')],
    'Next 7 Days': [moment(),moment().add(6, 'days')],
    'Next 14 Days': [moment(), moment().add(13, 'days')],
    'Next Month': [moment().add(1,'month'), moment().add(2,'month')],
    'Next 2 Months': [moment().add(1,'month'), moment().add(3,'month')],
    'Next 3 Months': [moment().add(1,'month'), moment().add(4,'month')],
    'Next 6 Months': [moment().add(1,'month'), moment().add(6,'month')],
    'Next  12 Months': [moment().add(1,'month'), moment().add(12,'month')],
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
  // $("input[name='origin']").val(query_data.origin);
  // $("input[name='transportmode']").val(query_data.transportmode);
  table.ajax.reload(setColor);
  //table.draw();
});

// Toggle document stats view 
$(document).on('mouseenter mouseleave','.stats',function() {
  $( this ).children().toggle( );
});

// Append loading and redraw datatable
$('#myModal').on('hidden.bs.modal', function (e){ 
  $('#myModal .modal-body').empty().append(loader);
  table.ajax.reload(setColor);
  
});

//For Settings
$('select[name="settings-dual"]').bootstrapDualListbox({
  nonSelectedListLabel: 'Non-selected Settings',
  selectedListLabel: 'Selected Settings',
  preserveSelectionOnMove: 'all',
  helperSelectNamePostfix:'_helper',
  sortByInputOrder: false,
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
      hideShowResetSettings();
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
    byLevel();
  },100);
  

}).find('option:selected').map(function() {return this.value}).get();

var check_arr = [];
function getSettings(map){
  var Sdata = [];
  var opt_value = [];
  var temp = [];
  $('#bootstrap-duallistbox-selected-list_settings-dual  option').each(function(){
    console.log($(this).val());
    
    opt_value.push($(this).val());
    Sdata.push({index_name:$(this).data('text'),index_value:$(this).val(),index_check:true,index_lvl:$(this).attr('lvl')});
  }); 
  
  $('#bootstrap-duallistbox-nonselected-list_settings-dual  option').each(function(){
    opt_value.push($(this).val());
    Sdata.push({index_name:$(this).data('text'),index_value:$(this).val(),index_check:false,index_lvl:$(this).attr('lvl')});
  }); 

  if(check_arr.length != Sdata.length && check_arr.length != 0){
    $.each(check_arr,function(k,v){
      if($.inArray(v.index_value,opt_value) == -1){
        temp.push({index_name:v.index_name,index_value:v.index_value,index_check:v.index_check,index_lvl:v.index_lvl});
      }else{
        $.each(Sdata,function(okey,oval){
          if(oval.index_value == v.index_value){
            temp.push({index_name:oval.index_name,index_value:oval.index_value,index_check:oval.index_check,index_lvl:oval.index_lvl});
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
byLevel();
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

  //re order column
  table.on('column-reorder',function(e, settings, details){
    $('table tbody tr td:first-child').append(`${$(".parent-assign").html()}`);
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
            hideShowResetSettings();
          }
        });
      },100);
      
  });
  //end of reorder column

  //reset search
  $("#reset-search").on("click",function(){
    $("#addvance-search-form").find("input[type=text], textarea").val("");
    table.ajax.reload(setColor);
  });

  $(document).on('click','tr td',function(e){
    var cl = $(e.target).attr("class");
    var indx = $(this).index();
    // if(cl.indexOf('macro') >=0 && indx == 0){
    //   $('tbody').find('tr.child').remove();
    // }
  });

  //reset settings
  $(document).on('click','#reset-settings',function(){
    var id = $(this).attr('data-setting-id');
    $.ajax({
      url: document.location.origin + '/user/deleteUserSettings/',
      type: "POST",
      dataType:"json",
      data:{"settings_id":id},
      success:function(res){
        // for checking only
        console.log(res);
      }
    });
    window.location.reload();
  });

  $(document).on("click",".assign-button",function(){
    var id = $(this).parent().parent().attr("ship-assign-id");
    $('.assign').attr('data-shipid',id);
  });
  
});

var setColor = function(){
  $(".doc-stats span").each(function(){
    if($(this).text()=="Missing"){
      $(this).parent().parent().addClass("missing-background");
    }else if($(this).text()=="Pending"){
      $(this).parent().parent().addClass("pending-background");
    }else if($(this).text()=="Approved"){
      $(this).parent().parent().addClass("approved-background");
    }
  });
};

function parse_query_string(query) {
  var vars = query.split("&");
  var query_string = {};
  for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split("=");
    var key = decodeURIComponent(pair[0]);
    var value = decodeURIComponent(pair[1]);
    // If first entry with this name
    if (typeof query_string[key] === "undefined") {
      query_string[key] = decodeURIComponent(value);
      // If second entry with this name
    } else if (typeof query_string[key] === "string") {
      var arr = [query_string[key], decodeURIComponent(value)];
      query_string[key] = arr;
      // If third or later entry with this name
    } else {
      query_string[key].push(decodeURIComponent(value));
    }
  }
  return query_string;
}

function byLevel(){
  var listbox  = $('#bootstrap-duallistbox-selected-list_settings-dual  option');
  var listboxparent = $('#bootstrap-duallistbox-selected-list_settings-dual');
  var level = []; 
  if(listbox.length > 0){
    listboxparent.append(`<optgroup id='ship-level' label='Shipment Level'></optgroup>`);
    listboxparent.append("<optgroup id='document-level' label='Document Level'></optgroup>");
    listbox.each(function(){
      if($(this).attr('lvl')=='shipment'){
        $('#ship-level').append($(this));
      }
      if($(this).attr('lvl')=='document'){
        $('#document-level').append($(this));
      }
    });
  }
  console.log(level);
  
}

// Button Request
$('button#request').click(function(e) {
  var url = "/document/request/" + shipment_id + "/" + document_type;
  preloader(url);
});

// Show loader
function preloader(url) {

  $("#myModal .modal-body").append(loader);

  // load the url and show modal on success
  $("#myModal .modal-body").load(url, function(response, status, xhr) { 
    if(xhr.status == 200){
      $('#loader-wrapper').remove();
      $("#myModal").modal("show"); 
    } else {
      alert("Error: " + xhr.status + ": " + xhr.statusText);
      $('#loader-wrapper').remove();
    }
  });
}

function hideShowResetSettings(){
  var id = userReset.user_info[0].user_id;
  $.ajax({
    url: document.location.origin + '/user/getUserResetId/',
    type: "POST",
    dataType:"json",
    data:{"useridreset":id},
    success:function(res){
      // for checking only
      if(res){
        var html = `<button id="reset-settings" type="button" data-setting-id="${res[0].id}" class="btn btn-block btn-danger">Reset Settings</button>`
        $(".parent-settings").html(html);
      }
    }
  });
}