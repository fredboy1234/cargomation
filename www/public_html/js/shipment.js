//JS script

// if (!window.location.origin) {
//   window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
// }

$(document).ready(function () {
  invokeFilter("", 1);
  loadRecentSave();
  var searchJson = [];
  $("#add_filters").on("change",function (e) { 
    var selected = $(this).find('option:selected').val();
    addSearchFilter(selected);
  });
  // Delete the form fieed row
  $("body").on("click", ".remove_node_btn_frm_field", function () {
    $(this).closest(".form_field_outer_row").remove();
    $(this).closest(".form_field_outer_row").attr('trashid');
    var elem = $(".form_field_outer").find(".form_field_outer_row");
    if(elem.length == 1) {
      elem.find('[id^=no_cond]').prop("disabled", true).addClass('exclude');
    } else {
      $('#no_cond_'+elem.length).prop("disabled", true).addClass('exclude');
    }
  });
  // Clone method
  $("body").on("click", ".add_node_btn_frm_field", function (e) {
    e.preventDefault();
    //var index = $(e.target).closest(".form_field_outer").find(".form_field_outer_row").length + 1;
    var index = $(".form_field_outer .form_field_outer_row").map(function() {
      return parseFloat($(this).attr('section'))+1;
    }).get().sort().pop();
    
    var cloned_el = $(e.target).closest(".form_field_outer_row").clone(true);

    $(e.target).closest(".form_field_outer").last().append(cloned_el).find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false);

    $(e.target).closest(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true);
   
    //change id
   
    
    // $(e.target)
    //   .closest(".form_field_outer")
    //   .find(".form_field_outer_row")
    //   .last()
    //   .find("input[type='text']")
    //   .attr("id", "mobileb_no_" + index);

    // $(e.target)
    //   .closest(".form_field_outer")
    //   .find(".form_field_outer_row")
    //   .last()
    //   .find("select")
    //   .attr("id", "no_type_" + index);
    
    $(cloned_el).attr("section",index);
    $(cloned_el).find('optgroup option').attr("data-index",index);
    $(cloned_el).find(".search-list").attr("id","no_search_"+index);
    $(cloned_el).find("select[name='type[]']").attr("id","no_type_"+index);
    $(cloned_el).find("input[name='value[]']").attr("id","no_value_"+index);
    $(cloned_el).find(".add_node_btn_frm_field").attr("section",index);
    $(e.target).closest(".form_field_outer").find(".exclude").attr("disabled",false);
    $(e.target).closest(".form_field_outer").find(".exclude").last().attr("disabled",true);
    var sdex = $(this).attr("section");
    var searchValue = $(e.target).closest(".form_field_outer").find("#no_search_"+sdex+" option:selected").val();
    $(cloned_el).find(".search-list option[value='"+searchValue+"']").attr("selected", true);
    
    //count++;
  });

  $('[data-toggle="tooltip"]').tooltip()
});

$(document).on('keypress',"input[name='value[]'], .add_node_btn_frm_field, input[id*='no_value_']",function(e){
  
  if(e.which ==13){
    e.preventDefault();
    e.stopPropagation();
  }
});
function invokeFilter(selected, index) {
  var $select = $(`#add_filters, #no_search_${index}`); 
  var text = '<option value="" selected="" disabled="" hidden="">Add search option</option>';
  $.getJSON('/settings/search-filter.json', function(data) {
    
    searchJson = data;
  $.each(data, function(key, value) {
    var parseText = key.replace(/_/g," ");
    parseText =  parseText.toLowerCase().replace(/\b[a-z]/g, function(letter) {
      return letter.toUpperCase();
    });
    text += `<optgroup label="${parseText}">`;
    $.each(value, function(key2, value2) {
      text += `<option data-type="${value2.filterType}" value="${value2.filterName}" data-index="${index}" `;
        if(value2.filterName == selected) {
          text += `selected`;
        }
      text += `>${value2.filterID}</option>`;
    });
    text += `</optgroup>`;
  });
    $select.html(text);
    $("#add_filters").val("");
  }).fail(function(){
    console.log("Error");
  });
}

function addSearchFilter(selected) {  
  $(".form_field_outer").find(".exclude").prop("disabled", false).removeClass('exclude');
  //var index = $(".form_field_outer").find(".form_field_outer_row").length + 1;
  var index = $(".form_field_outer .form_field_outer_row").map(function() {
        return parseFloat($(this).attr('section'))+1;
    }).get().sort().pop();
  $(".form_field_outer").append(`
  <div class="row form_field_outer_row ${index}" section="${index}">
    <div class="form-group col-md-3">
      <select name="search[]" id="no_search_${index}" class="form-control search-list" data-index="${index}">
        <option>--Select type--</option>
      </select>
    </div>
    <div class="form-group col-md-2">
      <select name="type[]" id="no_type_${index}" class="form-control no_type">
        <option>--Select type--</option>
      </select>
    </div>
    <div class="form-group col-md-4">
      <input name="value[]" id="no_value_${index}" type="text" class="form-control w_90" placeholder="Enter search value" />
    </div>
    <div class="form-group col-md-1">
      <select name="cond[]" id="no_cond_${index}" class="form-control exclude" disabled>
        <option value="OR">OR</option>
        <option value="AND">AND</option>
      </select>
    </div>
    <div class="form-group col-md-2 add_del_btn_outer">
      <button class="btn_round add_node_btn_frm_field" title="Copy or clone this row" section="${index}">
        <i class="fas fa-copy"></i>
      </button>

      <button class="btn_round remove_node_btn_frm_field" disabled>
        <i class="fas fa-trash-alt"></i>
      </button>
    </div>
  </div>
  `);
  $(".form_field_outer").find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false);
  $(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true);
  invokeFilter(selected, index);
}

var loader = '<div id="loader-wrapper" class="d-flex justify-content-center">' +
  '<div class="spinner-border" role="status">' +
  '<span class="sr-only">Loading...</span>' +
  '</div>' +
  '</div>';

var query_string = window.location.search.substring(1);
var parsed_qs = parse_query_string(query_string);

// TEMPORARY
if (parsed_qs['request'] === 'true') {
  // Button Request
  $(document).ready(function (e) {
    console.log("Running....");
    var url = "shipment/document/" + parsed_qs['shipment_num'] + "/" + parsed_qs['type'];
    preloader(url); 
  });

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
}

//show documents modal
$(document).on('click', '.doc', function (e) {
  e.preventDefault(); var type = "";
  if ($(this).data('type')) { type = "/" + $(this).data('type'); }
  $('#myModal').modal('show').find('.modal-body').load("/shipment/document/" + $(this).data('id') + type);
  var row_id = $(this).parent().parent().parent().attr('id');
  localStorage.setItem("row_id", row_id);
});

//assign shipment to users
// $(document).on("click", ".assign, .btn-assign", function () {
//   var userId = $(this).data("userid");
//   var shipId = $(this).data("shipid");
//   $.ajax({
//     url: "/shipment/shipmentAssign",
//     type: "POST",
//     data: { "user_id": userId, "shipment_id": shipId },
//     success: function (res) {
//       $(document).Toasts('create', {
//         title: 'Success',
//         body: 'Shipment was successfully assigned',
//         autohide: true,
//         close: false,
//         class: 'bg-success'
//       })
//     }
//   });
// });

//unassign shipment to users
$(document).on("click", ".btn-unassign", function () {
  var userId = $(this).data("userid");
  var shipId = $(this).data("shipid");

  $.ajax({
    url: document.location.origin + "/doctracker/shipmentunAssign",
    type: "POST",
    data: { "user_id": userId, "shipment_id": shipId },
    success: function (res) {
      $(document).Toasts('create', {
        title: 'Success',
        body: 'Shipment was successfully Unassigned',
        autohide: true,
        close: false,
        class: 'bg-success'
      })
    }
  });
});

//asssigned all
// $(document).on("click", "#assignall", function () {
//   var pp = $(this).parent().parent().find('.dropdown-item');
//   var count = 0;
//   assignBulk(pp, 'shipmentAssign');
// });

// $(document).on("click", "#unassign", function () {
//   var pp = $(this).parent().parent().find('.dropdown-item');
//   var count = 0;
//   assignBulk(pp, 'shipmentunAssign');
// });

$(document).ready(function () {

  if (parsed_qs.calendar != '' && typeof parsed_qs.calendar != 'undefined') {
    var c_date = new Date(parsed_qs.calendar);
    var curr_date = ("0" + (c_date.getDate() + 1)).slice(-2);
    var curr_month = ("0" + (c_date.getMonth() + 1)).slice(-2);
    var curr_year = c_date.getFullYear();
    calendar_end_date = curr_month + '/' + curr_date + '/' + curr_year;
    calendar_start_date = parsed_qs.calendar;
    $("input[name='ETA'], input[name='ETD']").val(calendar_start_date + ' - ' + calendar_end_date);
    $("input[name='post_trigger']").val("set");
    setTimeout(function () {
      $("#advance-search-btn").trigger('click');
    }, 1000);

  }

  if( typeof parsed_qs.transport_mode !=="undefined" ){
    if(parsed_qs.transport_mode == "air"){
      $("input[name='transportmode_sea']").prop( "checked", false );
      $("input[name='transportmode_air']").prop( "checked", true );
    }else if(parsed_qs.transport_mode == "sea"){
      $("input[name='transportmode_sea']").prop( "checked", true );
      $("input[name='transportmode_air']").prop( "checked", false );
    }

    $("input[name='post_trigger']").val("set");
    setTimeout(function () {
      $("#advance-search-btn").trigger('click');
    }, 1000);
  }

  if(typeof parsed_qs.pol !== "undefined"){
    $("input[name='pol']").val(parsed_qs.pol);
    
    setTimeout(function () {
      $("#advance-search-btn").trigger('click');
    }, 1000);
  }

  var tableColumnData = [];

  var sortedData = JSON.parse(userData);
  sortedData.sort(function(a, b) {
    return parseInt(a.index_value) - parseInt(b.index_value);
  })

  console.log(sortedData);

  $.each(sortedData, function (okey, oval) {
   $index = oval.index;
   $sort = (oval.index_sortable === 'true');
    if(oval.index != null){
      if(typeof oval != null || typeof oval != "undefined") {
        $index = oval.index;
      } 
    }
    tableColumnData.push({ data: $index, sortable: $sort });
  });

  var table = $('.table').DataTable({
    searching: true,
    paging: true,
    pagingType: "full_numbers",
    info: true,
    responsive: true,
    columnDefs: [
      {
        targets: [0],
        visible: false,
        searchable: false
      },
      {
        targets: [1],
        render: function (data, type, row) {
          return '<span>' + data + '</span>'
        }
      },
    ],
    autoWidth: false,
    lengthChange: false,
    colReorder: true,
    processing: true,
    language: {
      processing: '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>'
    },
    serverSide: true,
    serverMethod: 'post',
    columns: tableColumnData,
    order: [[0, 'desc']],
    ajax: {
      url: '/shipment/shipmentData/',
      data: function (d) {
        var arr = [];
        console.log(parsed_qs);
        if (typeof parsed_qs['value'] !== "undefined") {
          var search = parsed_qs['search'];
          var type = parsed_qs['type'];
          var value = parsed_qs['value'];
          var cond = "";
          if(typeof parsed_qs['value'] !== "undefined") {
            cond = parsed_qs['con'];
          }
          arr.push({
            "columnname": search,
            "type": type,
            "value": value.toUpperCase(),
            "cond": cond
          });
          d.data = arr;
        } else {
          $(".form_field_outer_row").each(function(){
            var search = $(this).find("[name*='search']").val(); //*= means like
            var type = $(this).find("[name*='type']").val(); 
            var value = $(this).find("[name*='value']").val(); 
            var cond = $(this).find("[name*='cond']").val();
            if($(this).find("[name*='cond']").hasClass('exclude')) {
               cond = "";
            } 
            //convert date to mm/dd/yyyy for api request format
            
            if(search === "ETA" || search === "ETD"){
             var dateString = value;
             var dateParts = dateString.split("-");
             var datePartsChild1 = dateParts[0].split("/");
             var datePartsChild2 = dateParts[1].split("/");
               var dateObject1 = new Date(+datePartsChild1[2], datePartsChild1[1] - 1, +datePartsChild1[0]); 
               var dateObject2 = new Date(+datePartsChild2[2], datePartsChild2[1] - 1, +datePartsChild2[0]); 
               value = moment(dateObject1.toString()).format('MM/DD/YYYY') + ' - '+moment(dateObject2.toString()).format('MM/DD/YYYY');
               type = 'DATERANGE';
            }
            
            if(typeof value !== null || value.length > 0){
              arr.push({
                 "columnname": search,
                 "type": type,
                 "value": value.toUpperCase(),
                 "cond": cond
              });
            } 
            d.data = arr;
          });
        }
      },
      error:function(err, status){
        // what error is seen(it could be either server side or client side.
        console.log(err);
      },
    },
    rowId: 'real_id_shipment',
    select: true,
    // bSort: true,
    ordering: true,
    // bFilter: true,
    // deferRender: true,
    // sDom: 'lrtip',
    // sEcho: true,
    // stateSave: true,
    // cache:true,
    // destroy: true,
    // fnCreatedRow: function (nRow, aData, iDataIndex) {
    //   //$('td', nRow).eq(0).append(`${$(".parent-assign").html()}`);
    //   //$(nRow).attr("ship-assign-id", aData['real_id_shipment']);
    //   //$('.assign').attr('data-shipid', aData['real_id_shipment']);
    //   // console.log(aData.eta);
    //   var selector = $(aData.eta).attr("data-date");
      
    //   $(nRow).find('td .datesort').parent().attr('data-order',selector);
    //   $(nRow).find('td .datesort').parent().attr('data-sort',selector);
      
    // },
    // columnDefs: [
    //   { className: "stats", targets: [4, 5, 6, 7, 8] }
    // ],
    initComplete: function( settings, json ) {
      setColor();
    },
    drawCallback: function( settings, json ) {
    },
    "fnPreDrawCallback": function (oSettings) {
      // if ($('#test').val() == 1) {
      //   return false;
      // }
      setColor();
    }
  })
  .on('draw.dt', function () {
    var info = table.page.info();
    // console.log('PAGE: ' + info.page);
    setColor();
    // $('#pageInfo').html('Showing page: ' + info.page + ' of ' + info.pages);
    loadRecentSave();
  });

  $('#addvance-search-form').on("submit", function(ev) {
    ev.preventDefault();
    var reqHandler = ['is_blank','not_blank'];
    var reqIdentity = '';
    var allBlank = true;
    var checkInput = [];
      $('input',this).each(function(index, el){
        if ($(el).val().length != 0) allBlank = false;
        checkInput.push($(el).val());
      });

      
      $("[id*='no_type_']").each(function(){
        reqIdentity = $(this).val();
        var comp = $(this).attr('id').split("_");
        var lastcomp = $(comp).get(-1);
        if(!$.inArray(reqIdentity, reqHandler) && $("#no_value_"+lastcomp).val().length == 0){
          allBlank = false;
        }
      });
      
      if(allBlank){
        Swal.fire('Please Add Search Parameter!')
      }else{
        table.ajax.reload(setColor);
      }
  
    // $.ajax({
    //   url: "/shipment/shipmentData/"+user_id+"/"+role_id,
    //   type: "POST",
    //   data: {arr},
    //   beforeSend: function(res) {
        //table.ajax.reload(setColor);
    //   },
    //   success: function (res) {
    //     // $(document).Toasts('create', {
    //     //   title: 'Success',
    //     //   body: 'Shipment was successfully Unassigned',
    //     //   autohide: true,
    //     //   close: false,
    //     //   class: 'bg-success'
    //     // })
    //   }
    // });
  });
  $.fn.dataTable.ext.errMode = 'none';
  // Doing some magic! 
  $('.table tbody').on('click', 'tr td', function(e) { 
    if( $(e.target).closest('span').length == 0 ){
      return;
    }
    $(this).click();
  });

  if (parsed_qs.doc !== '' && typeof parsed_qs.doc != 'undefined') {
    table.search(parsed_qs.doc).draw();
  }

  //on search data table
  $('#doc_search').on('keyup', function () {
    table.search(this.value).draw();
  });


  //ETA time picker
  var start = moment().subtract(29, 'days');
  var end = moment();

  $('input[name="ETA"], input[name="ETD"]').daterangepicker({
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
      'Next 7 Days': [moment(), moment().add(6, 'days')],
      'Next 14 Days': [moment(), moment().add(13, 'days')],
      'Next Month': [moment().add(1, 'month'), moment().add(2, 'month')],
      'Next 2 Months': [moment().add(1, 'month'), moment().add(3, 'month')],
      'Next 3 Months': [moment().add(1, 'month'), moment().add(4, 'month')],
      'Next 6 Months': [moment().add(1, 'month'), moment().add(6, 'month')],
      'Next  12 Months': [moment().add(1, 'month'), moment().add(12, 'month')],
    },
    locale: {
      //format: 'M/DD hh:mm A'
      format: defaultDate
    }
  });

  //on hide calendar set start date and end date
  $('input[name="ETA"], input[name="ETD"]').on('apply.daterangepicker', function (ev, picker) {
    $(this).val(picker.startDate.format(defaultDate) + ' - ' + picker.endDate.format(defaultDate));
  });

  // Toggle document stats view 
  $(document).on('mouseenter mouseleave', '.stats', function () {
    var elem = $(this).html();
    if($(elem).hasClass('vesshe') && $(elem).attr('href') !=''){
      $(elem).show();
    }else{
      $(this).children().toggle();
    }
  });

  // Append loading and redraw datatable
  $('#myModal').on('hidden.bs.modal', function (e) {
    $('#myModal .modal-body').empty().append(loader);
    if (sessionStorage.getItem("changeTriggered") != null) {
      table.ajax.reload(function () {
        setColor();
        var stId = $('#' + localStorage.getItem("row_id"));
        // $('html, body').animate({ scrollTop: stId.offset().top }, 2000);
        $('html, body').animate({ scrollTop: stId.offset() }, 2000);
        $(stId).addClass('selected');
      }, false);
      sessionStorage.clear();
    }
  });

  //For Settings
  $('select[name="settings-dual"]').bootstrapDualListbox({
    nonSelectedListLabel: 'Available Columns',
    selectedListLabel: 'Showing Columns',
    preserveSelectionOnMove: 'all',
    helperSelectNamePostfix: '_helper',
    sortByInputOrder: false,
    filterTextClear: "",
    selectorMinimalHeight: 300 
  });
  CustomizeDuallistbox('settings');
  function CustomizeDuallistbox(listboxID) {
    var customSettings = $('#' + listboxID).bootstrapDualListbox('getContainer');
    var buttons = customSettings.find('.btn.moveall, .btn.move, .btn.remove, .btn.removeall');
  
    customSettings.find('.box1, .box2').removeClass('col-md-6').addClass('col-md-4');
    customSettings.find('.box1').after('<div class="customButtonBox col-md-1 text-center"></div>');
   // customSettings.find('.customButtonBox').append(buttons);
  
    //customSettings.find('.btn-group.buttons').remove();
  }
  //chick on click button all 
  var wasClicked = false;
  $(document).on('click', '.moveall, .removeall', function (e) {
    wasClicked = true;
  });

  var map = $('select[name="settings-dual"]').on("change", function (e) {
    setTimeout(function () {
      var comp = $("select[name='settings-dual'] option:selected").map(function () {
        return this.value;
      }).get(),

        set1 = map.filter(function (i) {
          return comp.indexOf(i) < 0;
        }),
        set2 = comp.filter(function (i) {
          return map.indexOf(i) < 0;
        }),
        last = (set1.length ? set1 : set2)[0];

      map = comp;
      var Sdata = getSettings(map);

      $.ajax({
        url: document.location.origin + '/shipment/addUserSettings/',
        type: "POST",
        dataType: "json",
        data: { "settings": Sdata },
        success: function (res) {
          // for checking only
          hideShowResetSettings();
        }
      });

      if (wasClicked) {
        if (map.length > 0) {
          $.each(map, function (okey, oval) {
            var column = table.column(oval);
            column.visible(column);
          });
        } else {
          var column = table.columns();
          column.visible(!column.visible());
        }

        wasClicked = false;
      } else {
        var column = table.column(last);
        column.visible(!column.visible());
      }
      byLevel();
    }, 100);


  }).find('option:selected').map(function () { return this.value }).get();

  var check_arr = [];
  function getSettings(map) {
    var Sdata = [];
    var opt_value = [];
    var temp = [];
    $('#bootstrap-duallistbox-selected-list_settings-dual  option').each(function () {
      opt_value.push($(this).val());
      Sdata.push({ 
        index: $(this).data('index'),
        index_name: $(this).data('text'), 
        index_value: $(this).val(), 
        index_check: true, 
        index_lvl: $(this).attr('lvl'),
        index_sortable: $(this).data('sort'), });
    });

    $('#bootstrap-duallistbox-nonselected-list_settings-dual  option').each(function () {
      opt_value.push($(this).val());
      Sdata.push({ 
        index: $(this).data('index'),
        index_name: $(this).data('text'), 
        index_value: $(this).val(), 
        index_check: false, 
        index_lvl: $(this).attr('lvl'),
        index_sortable: $(this).data('sort'), });
    });

    if (check_arr.length != Sdata.length && check_arr.length != 0) {
      $.each(check_arr, function (k, v) {
        if ($.inArray(v.index_value, opt_value) == -1) {
          temp.push({ index_name: v.index_name, index_value: v.index_value, index_check: v.index_check, index_lvl: v.index_lvl });
        } else {
          $.each(Sdata, function (okey, oval) {
            if (oval.index_value == v.index_value) {
              temp.push({ index_name: oval.index_name, index_value: oval.index_value, index_check: oval.index_check, index_lvl: oval.index_lvl });
            }
          });
        }
      });
      Sdata = [];
      Sdata = temp;
    }
    check_arr = Sdata;
    return Sdata;
  }
  byLevel();
  var hide = getSettings();
  $.each(hide, function ($key, $val) {
    if ($val.index_check == false) {
      var column = table.column($val.index_value);
      column.visible(!column.visible());
    }
  });

  $('.moveall').text('Show All');
  $('.removeall').text('Hide All');
  //end of settings

  //re order column
  table.on('column-reorder', function (e, settings, details) {
    // $('table tbody tr td:first-child').append(`${$(".parent-assign").html()}`);
    $("select[name='settings-dual'] option").each(function () {
      if ($(this).val() == details.from) {
        $(this).val(details.to);
        $(this).attr('id', details.to);
      } else if ($(this).val() == details.to) {
        $(this).val(details.from);
        $(this).attr('id', details.from);
      }
    });
    $('#bootstrap-duallistbox-selected-list_settings-dual  option').each(function () {
      if ($(this).val() == details.from) {
        $(this).val(details.to);
        $(this).attr('id', details.to);
      } else if ($(this).val() == details.to) {
        $(this).val(details.from);
        $(this).attr('id', details.from);
      }
    });
    setTimeout(function () {
      var Sdata = getSettings();
      $.ajax({
        url: document.location.origin + '/shipment/addUserSettings/',
        type: "POST",
        dataType: "json",
        data: { "settings": Sdata },
        success: function (res) {
          hideShowResetSettings();
        }
      });
    }, 100);

  });
  //end of reorder column

  //reset search
  $("#reset-search").on("click", function () {
    $("input[name='post_trigger']").val('');
    $("#addvance-search-form").find("input[type=text], textarea").val("");
    table.ajax.reload(setColor);
    //table.ajax.reload();
  });

  $("#clearFilter").on("click",function(){
    var search = $('#no_search_1').val();
    var value = $('#no_value_1').val();
    $(".form_field_outer_row:not(:first)").remove();
    $("#no_value_1").val("");
    $("#no_search_1").val("");
    $("#no_type_1").prop("selectedIndex", 0);
    $("#add_filters option").each(function(){
      $(this).attr("data-index",1);
    });
    if((typeof search !== null || search !== null) && value !== '') {
      table.ajax.reload(setColor);
    }
  });
  
  // $(document).on('click', 'tr td', function (e) {
  //   var cl = $(e.target).attr("class");
  //   var indx = $(this).index();
  //   // if(cl.indexOf('macro') >=0 && indx == 0){
  //   //   $('tbody').find('tr.child').remove();
  //   // }
  // });

  //reset settings
  $(document).on('click', '#reset-settings', function () {
    var id = $(this).attr('data-setting-id');
    $.ajax({
      url: document.location.origin + '/user/deleteUserSettings/',
      type: "POST",
      dataType: "json",
      data: { "settings_id": id },
      success: function (res) {
      }
    });
    window.location.reload();
  });

  $(document).on("click", ".assign-button", function () {
    var id = $(this).parent().parent().attr("ship-assign-id");
    $('.assign').attr('data-shipid', id);
  });

  $("#drop-search").on("keyup", function () {
    var searchval = $(this).val().toLowerCase();
  });

  var height = $(window).height();
  $('.ttable, #DataTables_Table_0_wrapper').height(height);
});

var setColor = function () {

  if (theme == 'template_one') {
    $(".doc-stats span").each(function () {
      if ($(this).text() == "Missing") {
        $(this).parent().parent().addClass("bg-danger");
      } else if ($(this).text() == "Pending") {
        $(this).parent().parent().addClass("bg-warning");
      } else if ($(this).text() == "Approved") {
        $(this).parent().parent().addClass("bg-success");
      }
    });
  } else {
    $(".doc-stats span").each(function () {
      if ($(this).text() == "Missing") {
        $(this).parent().parent().addClass("stats missing-background");
      } else if ($(this).text() == "Pending") {
        $(this).parent().parent().addClass("stats pending-background");
      } else if ($(this).text() == "Approved") {
        $(this).parent().parent().addClass("stats approved-background");
      } else if ($(this).text() == "Requested") {
        $(this).parent().parent().addClass("stats requested-background");
      }
    });
  }
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

function byLevel() {
  var listbox = $('#bootstrap-duallistbox-selected-list_settings-dual  option');
  var listboxparent = $('#bootstrap-duallistbox-selected-list_settings-dual');
  var level = [];
  if (listbox.length > 0) {
    listboxparent.append(`<optgroup id='ship-level' label='Shipment Level'></optgroup>`);
    listboxparent.append("<optgroup id='document-level' label='Document Level'></optgroup>");
    listbox.each(function () {
      if ($(this).attr('lvl') == 'shipment') {
        $('#ship-level').append($(this));
      }
      if ($(this).attr('lvl') == 'document') {
        $('#document-level').append($(this));
      }
    });
  }
}

//check and uncheck transport mode
$(".no_trapo").on('click',function(){
  $( "input[name='transportmode_air']" ).prop( "checked", false );
  $( "input[name='transportmode_sea']" ).prop( "checked", false );
});
$( "input[name='transportmode_sea'], input[name='transportmode_air']" ).on('click',function(){
  $( "input[name='transportmode_none']" ).prop( "checked", false );
});
// Button Request
$('button#request').click(function (e) {
  var url = "/document/request/" + shipment_id + "/" + document_type;
  preloader(url);
  $('#document_action, #go_back').toggle();
});

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

  //$('button#request').toggle();
}

function hideShowResetSettings() {
  var id = userReset.user_info[0].user_id;
  $.ajax({
    url: document.location.origin + '/user/getUserResetId/',
    type: "POST",
    dataType: "json",
    data: { "useridreset": id },
    success: function (res) {
      // for checking only
      if (res) {
        var html = `<button id="reset-settings" type="button" data-setting-id="${res[0].id}" class="btn btn-block btn-danger">Set Default</button>`
        $(".parent-settings").html(html);
      }
    }
  });
}

//need to jquery no time
function filterFunction() {
  var input, filter, ul, li, a, i;
  input = document.getElementById("drop-search");
  filter = input.value.toUpperCase();
  div = document.getElementById("drop-list");
  a = div.getElementsByTagName("a");
  for (i = 0; i < a.length; i++) {
    txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "";
    } else {
      a[i].style.display = "none";
    }
  }
}

function assignBulk(pp, api) {
  var count = 0;
  $('#loadermodal').modal('show');
  $(pp).each(function () {
    var userId = $('button', this).attr('data-userid');
    var shipId = $('button', this).attr('data-shipid');
    var text = $.trim($('button', this).text());
    var msg = text == 'Assign' ? 'Assigned' : 'Unassigned';

    $.ajax({
      url: document.location.origin + "/doctracker/" + api,
      type: "POST",
      data: { "user_id": userId, "shipment_id": shipId },
      success: function (res) {
        count++;
        if (pp.length == count) {
          $('#loadermodal').modal('hide');
          $(document).Toasts('create', {
            title: 'Success',
            body: ' ' + msg + ' ',
            autohide: true,
            close: false,
            class: 'bg-success'
          })
        }
      }
    });
  });
}

//////////
$(document).on('click','.stats',function(){
  var elem = $(this).html();
  if($(elem).hasClass('vesshe') && $(elem).attr('href') !=''){
    window.location.replace(document.location.origin+$(elem).attr('href'));
  }
});

function showInfo(shipment_num) {
  var url = "shipment/info/"+ user_id +"/" + shipment_num;
  // $("#shipmentModal .modal-body").append(loader);
  $(loader).insertAfter('#shipmentModal');

  // load the url and show modal on success
  $("#shipmentModal .modal-body").load(url, function (response, status, xhr) {
    if (xhr.status == 200) {
      // $('#loader-wrapper').remove();
      $('#shipmentModal').next().remove();
      $("#shipmentModal").modal("show");
    } else {
      alert("Error: " + xhr.status + ": " + xhr.statusText);
      $('#shipmentModal').next().remove();
      // $('#loader-wrapper').remove();
    }
  });

  $('button#request').toggle();
}

function macroLink(link) {
  Swal.fire({
    title: "This action will open WiseCloud",
    text: "Please make sure you already installed it?",
    icon: "warning",
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: `Open WiseCloud`,
    denyButtonText: 'No',
    customClass: {
      actions: 'my-actions',
      cancelButton: 'order-1 right-gap',
      confirmButton: 'order-2',
      denyButton: 'order-3',
    }
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = link;
    }
    if (result.isDenied) {
      Swal.fire("No action");
    } else if (result.isDismissed) {
      Swal.fire("You cancel your action!");
    } else if(res.dismiss == 'esc'){
      console.log('cancle-esc**strong text**');
    }
  });
}

$(".search-fil").on("click",function(e){
  e.preventDefault();
  filterRequest($("#addvance-search-form").serializeArray());
});

function filterRequest(data){
  var dataHolder = [];
  var parentDataHolder = [];
  var counter = 1;
  $.each(data,function(okey,oval){
    if(counter <= 3 ){
      dataHolder.push({fieldname:oval.name,fieldval:oval.value,fieldcon:"test3",fielde:"test4"});
    }else{
      dataHolder =[];
      counter = 1;
    }

    if(counter == 3){
      parentDataHolder.push(dataHolder);
    }
    counter++;
  });
   console.log(parentDataHolder);
}

$(document).on("change", ".search-list", function(){
  var index = $('option:selected',this).attr('data-index');
  var dataType = $('option:selected',this).attr("data-type");
  var data = [];
  
  if($(this).hasClass("add_new_frm_field_btn")){
    index = $(".form_field_outer .form_field_outer_row").map(function() {
      return parseFloat($(this).attr('section'));
    }).get().sort().pop();
  }
 
  data['id'] = "no_type_"+index;
  
  //data['options'] = triggerType(dataType);
  data['value'] = index;
  data['type'] = dataType;
  data['keyindex'] = $(this).val();

  triggerType(data);
  if(dataType !== "date"){
    $("#no_value_"+index).val("");
    var dateatt = $("#no_value_"+index).data('daterangepicker');
    if(typeof dateatt !== typeof undefined && dateatt !== false){
      $("#no_value_"+index).data('daterangepicker').remove();
    }
  }
 
 // appendToSelect(data);
});

function triggerType(data){
  console.log("from trigg");
  console.log(data['type']);
  var optionHTML = "";
  switch(data['type']) {    
    case 'date':
      $("#no_value_"+data['value']).val('');
      $("#container_mode_"+data['value']).select2('destroy');
      $("#no_value_"+data['value']).parent().empty().html(`
        <input name="value[]" id="no_value_${data['value']}" type="text" class="form-control w_90" placeholder="Enter search value">`);

      $("#no_value_"+data['value']).daterangepicker({
        locale: {
          format:defaultDate
        }
      });

    $('#'+data['id']).html(`
        <option class="datepick" data-date="${[moment().format(defaultDate),moment().format(defaultDate)]}">Today</option>
        <option class="datepick" data-date="${[moment().subtract(1, 'days').format(defaultDate),moment().subtract(1, 'days').format(defaultDate)]}">Yesterday</option>
        <option class="datepick" data-date="${[moment().subtract(1, 'weeks').format(defaultDate),moment().format(defaultDate)]}">Last Week</option>
        <option class="datepick" data-date="${[moment().subtract(6, 'days').format(defaultDate),moment().format(defaultDate)]}">Last 7 Days</option>
        <option class="datepick" data-date="${[moment().subtract(13, 'days').format(defaultDate),moment().format(defaultDate)]}">Last 14 Days</option>
        <option class="datepick" data-date="${[moment().subtract(29, 'days').format(defaultDate),moment().format(defaultDate)]}">Last 30 Days</option>
        <option class="datepick" data-date="${[moment().subtract(1, 'month').startOf('month').format(defaultDate),moment().subtract(1, 'month').endOf('month').format(defaultDate)]}">Last Month</option>
        <option class="datepick" data-date="${[moment().subtract(2, 'month').startOf('month').format(defaultDate),moment().subtract(1, 'month').endOf('month').format(defaultDate)]}">Last 2 Months</option>
        <option class="datepick" data-date="${[moment().subtract(3, 'month').startOf('month').format(defaultDate),moment().subtract(1, 'month').endOf('month').format(defaultDate)]}">Last 3 Months</option>
        <option class="datepick" data-date="${[moment().add(1, 'days').format(defaultDate),moment().add(1, 'days').format(defaultDate)]}">Tomorrow</option>
        <option class="datepick" data-date="${[moment().add(6, 'days').format(defaultDate),moment().add(12, 'days').format(defaultDate)]}">Next Week</option>
        <option class="datepick" data-date="${[moment().format(defaultDate),moment().add(6, 'days').format(defaultDate)]}">Next 7 Days</option>
        <option class="datepick" data-date="${[moment().format(defaultDate),moment().add(13, 'days').format(defaultDate)]}">Next 14 Days</option>
        <option class="datepick" data-date="${[moment().add(1, 'month').format(defaultDate),moment().add(2, 'month').format(defaultDate)]}">Next Month</option>
        <option class="datepick" data-date="${[moment().add(1, 'month').format(defaultDate),moment().add(3, 'month').format(defaultDate)]}">Next 2 Months</option>
        <option class="datepick" data-date="${[moment().add(1, 'month').format(defaultDate),moment().add(6, 'month').format(defaultDate)]}">Next 6 Months</option>
        <option class="datepick" data-date="${[moment().add(1, 'month').format(defaultDate),moment().add(12, 'month').format(defaultDate)]}">Next  12 Months</option>`);
    break;
    case 'input':
      $("#no_value_"+data['value']).val('');
      $("#container_mode_"+data['value']).select2('destroy');
      $("#no_value_"+data['value']).parent().empty().html(`
        <input name="value[]" id="no_value_${data['value']}" type="text" class="form-control w_90" placeholder="Enter search value">`);

      $('#'+data['id']).html(createSelect('number_and_preferences',data));
    break;
    case 'option':
        $('#'+data['id']).html(createSelect('others',data));
        selectTrigger(data['value'],'select2');
    break;

    case 'document':
        $('#'+data['id']).html(createSelect('document',data));
        selectTrigger(data['value'],'select2');
    break;
  }
}

function selectTrigger(index,inputType){
  if(inputType === "select2"){
    $('#container_mode_'+index).select2();
    $('#container_mode_'+index).on("select2:select", function (e) {
      var text = e.params.data.text;
      if (text == 'Select All') {
        $("#container_mode_"+index+" > option").prop("selected", "selected");
        $("#container_mode_"+index+" > option").each(function (key, val) {
          if ($(val).val() == 'all') {
            $(this).prop('selected', false);
          }
        });
        $("#container_mode_"+index).trigger("change");
      } 
      var array = $(this).select2('data');
      var element = [];
      for (const key in array) {
        if (Object.hasOwnProperty.call(array, key)) {
          element.push(array[key].text);
        }
      }
      $("#no_value_"+index).val(element.join());
    });
  }else if(type==="date"){

  }
  
}

//create dynamic select option base on serch title
function createSelect(key,data){
  console.log(data);
  console.log(key);
  var optionHTML = "";
   var handler = $.grep(searchJson[key], function(obj) { 
    if(obj.filterName === data['keyindex']){   
        return obj; 
      }
  });
 
  $.each(handler[0].type,function(okey,oval){
    var select = (okey==0 ? 'selected' :'');
    optionHTML +=`<option value="${oval.value}" ${select}>${oval.option}</option>`;
  });
  if(handler[0].value.length > 0){
    createSelectValue(data['value'],handler[0].value);
  }
  
  return optionHTML;
}

function createSelectValue(index,value){
  var optionHTML="";
  $.each(value,function(key,val){
    optionHTML+=`<option value="${val.value}">${val.name}</option>`;
  });
  $("#no_value_"+index).parent().empty().html(`
        <input name="value[]" id="no_value_${index}" type="hidden"> 
        <select id="container_mode_${index}" class="form-control w_90" multiple="multiple">${optionHTML}</select>`);
}

function appendToSelect(data){
  $('#'+data['id']).html(data['options']);
}

function dateFormat(date){
  return (((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + (date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + date.getFullYear();
}

$(document).on("change", "[id*='no_type_']",function(){
  if($("option:selected",this).hasClass("datepick")){
    var thisOption = $("option:selected",this);
    var fetchDate = thisOption.attr("data-date").split(",");
    var idOfDate = "#no_value_"+thisOption.parent().parent().parent().attr("section");
      $(idOfDate).daterangepicker({
        startDate:fetchDate[0],
        endDate: fetchDate[1],
        locale: {
          format: defaultDate
        }
      });
  }
});

/**************************
 * SAVE AND RECENT SEARCH
 *************************/
 var mini_loader = '<div id="loader-wrapper" class="d-flex justify-content-center position-absolute">' +
 '<div class="spinner-border" role="status">' +
 '<span class="sr-only">Loading...</span>' +
 '</div>' +
 '</div>';
// On Click Save Search
$('#savefilter').on("click",function(){
  var settingArray = [];
  $(".form_field_outer_row").each(function(){
    var search = $(this).find("[name*='search']").val(); //*= means like
    var type = $(this).find("[name*='type']").val(); 
    var value = $(this).find("[name*='value']").val(); 
    var cond = $(this).find("[name*='cond']").val();
    if($(this).find("[name*='cond']").hasClass('exclude')) {
        cond = "";
    } 
    if(typeof search !== null && search !== null){
      settingArray.push({
        "columnname": search,
        "type": type,
        "value": value.toUpperCase(),
        "cond": cond
      });
    } 
  });
  if(settingArray.length > 0){
    Swal.fire({
      title: 'Search Query Title',
      // html: `<input type="text" id="search_title" class="swal2-input" placeholder="Search Title">`,
      input: 'text',
      inputPlaceholder: 'Enter search title',
      inputAttributes: {
        maxlength: 50,
        autocapitalize: 'off',
        autocorrect: 'off'
      },
      confirmButtonText: 'Save',
      focusConfirm: false,
      focusDeny: false,
      focusCancel: false,
      showCloseButton: true,
      showCancelButton: true,
      showLoaderOnConfirm: true,
      preConfirm: (search_title) => {
        if (!search_title) {
          Swal.showValidationMessage(`Please enter search title`)
        } else {
          const api = "/shipment/putSaveSearch";
          var payload = {user_id:user_id, search_title:search_title, search:settingArray};
          $.post(api,payload)
          .done(function (result, status, xhr) {
            console.log(status);
          })
          .fail(function (xhr, status, error) {
              console.log("Result: " + status + " " + error + " " + xhr.status + " " + xhr.statusText)
          });
          return { search_title: search_title }
        }
      },
      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      if(result.isDismissed == true || result.isDenied == true) {
        Swal.fire(
          'Failed to saved!',
          `Search Title: ${result.value.search_title}`,
          'error'
        )
      } else {
        Swal.fire(
          'Saved Successfully!',
          `Search Title: ${result.value.search_title}`,
          'success'
        )
      }
    })
  } else {
    Swal.fire(
      'Failed to saved!',
      `No search filter to be save.`,
      'error'
    )
  }
});
// On Shown Load Recent/Save
$('#vert-tabs-save-tab').on('shown.bs.tab', function(event){
  loadRecentSave();
});
// Load Recent/Save Search
$('#loadSaved, #loadRecent').on('click', function() {
  var text = $('select#save_search, select#recent_search').find(":selected").data('value'); console.log(text);
  const myArray = text.split(",");
  if(myArray.length > 0){
    $(".form_field_outer_row:not(:first)").remove();
    $("#no_value_1").val("");
    $("#no_search_1").val("");
    $("#no_type_1").val("");
    $("#add_filters option").each(function(){
      $(this).attr("data-index",1);
    });
    var length = myArray.length;
    $(myArray).each(function(k,v){
      var field = "";
      if(v.includes("document")) {
        var field = v.split(':');
        field[0] = field[0] + ":" + field[1];
        field[1] = field[2];
        field[2] = field[3];
        field[3] = field[4];
        field.pop();
        console.log(field);
      } else {
        var field = v.split(':');
      }
      if(k < (length - 1)) {
        addSearchFilter(field[0]);
      }
      console.log(field);
      var xdex = k + 1; // 0 
      setTimeout(function(){
        $("#no_search_"+xdex).change(); // column
        $("#no_search_"+xdex).val(field[0]).trigger('change');
      },300);
      setTimeout(function(){
        $("#no_type_"+xdex).change();
        $("#no_type_"+xdex).val(field[1]).trigger('change');
      },300);
      setTimeout(function(){
        $("#no_cond_"+xdex).change();
        $("#no_cond_"+xdex).val(field[3]).trigger('change');
      },300);
      setTimeout(function(){
        $("#no_value_"+xdex).val(field[2]);
        $("#container_mode_"+xdex).val(field[2]);
        $("#container_mode_"+xdex).trigger('change'); 
      },300);
    });
  }
  console.log(myArray);
  $('a[href="#vert-tabs-search"]').tab('show');
});
// Reset Recent/Save Search
$('#resetSearch').on('click', function() {
  $("#save_search, #recent_search").prop('selectedIndex', -1);
});
// Toggle when select
$("#save_search").on('change', function(e) {
  // alert( $(this).find(":selected").val() );
  $("#recent_search").prop('selectedIndex', -1);
  $('#query_text').html(this.value);
});
// Toggle when select
$("#recent_search").on('change', function(e) {
  $("#save_search").prop('selectedIndex', -1);
  $('#query_text').html(this.value);
});
// Delete Recent/Save Search
$('#deleteSearch').on('click', function() {
  $('select').find(":selected").hide();
});
  // Func Recent/Save Search
  function loadRecentSave() {
    $.ajax({
      url: "/shipment/getRecentSave/",
      type: "post",
      data: {user_id:user_id},
      dataType: "json",
      beforeSend: function (res) {
        console.log("loading...");
        $("#fsearch > .card-body").append(mini_loader);
      },
      success: function (res) {
        $('#loader-wrapper').remove();
        const search_obj = JSON.parse(res.search);
        const recent_obj = JSON.parse(res.recent);
        search_obj.sort(function(a,b){
          return new Date(b.created_date) - new Date(a.created_date);
        });
        recent_obj.sort(function(a,b){
          return new Date(b.created_date) - new Date(a.created_date);
        });
        var search_html = "";
        var recent_html = "";
        for (const key in search_obj) {
          if (Object.hasOwnProperty.call(search_obj, key)) {
            const search = search_obj[key].search_query;
            var search_value = "";
            var search_data = "";
            for (const key2 in search) {
              if (Object.hasOwnProperty.call(search, key2)) {
                var columnname = search[key2].columnname;
                var type = search[key2].type;
                var value = search[key2].value;
                var cond = search[key2].cond;
                search_value += columnname + " : " + type + " : " + value + " : " + cond + "<br>";
                search_data += columnname + ":" + type + ":" + value + ":" + cond + ",";
              }
            }
            var text_save = search_obj[key].search_title;
            text_save = text_save.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
            search_html += '<option data-value="'+search_data.slice(0, -1)+'" value="'+search_value+'">' + 
            text_save + '</option>';
          }
        }
        for (const key in recent_obj) {
          if (Object.hasOwnProperty.call(recent_obj, key)) {
            const recent = recent_obj[key].search_query;
            var recent_value = "";
            var recent_data = "";
            for (const key2 in recent) {
              if (Object.hasOwnProperty.call(recent, key2)) {
                var columnname = recent[key2].columnname;
                var type = recent[key2].type;
                var value = recent[key2].value;
                var cond = recent[key2].cond;
                recent_value += columnname + " : " + type + " : " + value + " : " + cond + "<br>";
                recent_data += columnname + ":" + type + ":" + value + ":" + cond + ",";
              }
            }
            const text_recent = recent[0].columnname;
            recent_html += '<option data-value="'+recent_data.slice(0, -1)+'" value="'+recent_value+'">' + 
            text_recent.replaceAll('_', ' ').toUpperCase() + 
            " ("+recent[0].value+")" +
            '</option>';
          }
        }
        $('#save_search').html(search_html);
        $('#recent_search').html(recent_html);
      },
      error: function (res) {
        console.log(res);
        $('#loader-wrapper').remove();
      },
      complete: function (res) {
        console.log(res);
        $('#loader-wrapper').remove();
      },
    }).done(function(ev) {
      console.log(ev);
      $('#loader-wrapper').remove();
    });
  }