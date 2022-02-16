//JS script

// if (!window.location.origin) {
//   window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
// }

$(document).ready(function () {
  $("body").on("click", ".add_node_btn_frm_field",function (e) {
    var index = $(e.target).closest(".form_field_outer").find(".form_field_outer_row").length + 1;
    var cloned_el = $(e.target).closest(".form_field_outer_row").clone(true);

    $(e.target).closest(".form_field_outer").last().append(cloned_el).find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false);

    $(e.target).closest(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true);

    //change id
    $(e.target)
      .closest(".form_field_outer")
      .find(".form_field_outer_row")
      .last()
      .find("input[type='text']")
      .attr("id", "mobileb_no_" + index);

    $(e.target)
      .closest(".form_field_outer")
      .find(".form_field_outer_row")
      .last()
      .find("select")
      .attr("id", "no_type_" + index);

    console.log(cloned_el);
    //count++;
  });
});
$(document).ready(function(){ 
  $("body").on("click",".add_new_frm_field_btn", function (e){ 
    
    var $this = $(this);
    var optionHTML = "";
    var thisval = $this.attr("filname");
    var selected = "";
   // console.log(thisval);
    $.each(searchfilter,function(okey,oval){
      $.each(oval,function(key,val){
        if(val.filterName === thisval){
          optionHTML +=`<option selected>${val.filterID}</option>`;
        }else{
          optionHTML +=`<option>${val.filterID}</option>`;
        }
      });
    });
    
    e.preventDefault();
    var index = $(".form_field_outer").find(".form_field_outer_row").length + 1; 
    $(".form_field_outer").append(`
      <div class="row form_field_outer_row">
        <div class="form-group col-md-3">
          <select name="no_type[]" id="no_type_${$this.val()}" class="form-control" value="${$this.val()}">
            ${optionHTML}
          </select>
        </div>
        <div class="form-group col-md-2 text-center">
          <div class="dropdown">
              <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Select Filters
              </button>
              <div class="dropdown-menu px-2" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="javascript:void(0)">exact</a>
                <a class="dropdown-item" href="javascript:void(0)">starts with</a>
                <a class="dropdown-item" href="javascript:void(0)">contains</a>
                <a class="dropdown-item" href="javascript:void(0)">not equal</a>
                <a class="dropdown-item" href="javascript:void(0)">not contain</a>
                <a class="dropdown-item" href="javascript:void(0)">is blank</a>
                <a class="dropdown-item" href="javascript:void(0)">is not blank</a>
              </div>
          </div>
        </div>
      <div class="form-group col-md-5">
        <input type="text" class="form-control w_90" name="ETA" id="ETA" placeholder="" />
      </div>
      <button class="btn_round remove_node_btn_frm_field" disabled>
        <i class="fas fa-trash-alt"></i>
      </button>
    </div>
  </div>
`); 
  $(".form_field_outer").find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false); 
  $(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true); });

  $this.val("Search Filter");
}, testicle());

$("body").on("click", ".remove_node_btn_frm_field", function () {
  $(this).closest(".form_field_outer_row").remove();
});

function testicle () {
$('#no_type_').on('change', function() {
  $('input[name="ETA"], input[name="ETD"]').daterangepicker();
})
}


var loader = '<div id="loader-wrapper" class="d-flex justify-content-center">' +
  '<div class="spinner-border" role="status">' +
  '<span class="sr-only">Loading...</span>' +
  '</div>' +
  '</div>';

var query_string = window.location.search.substring(1);
var parsed_qs = parse_query_string(query_string);
console.log(parsed_qs);
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
  console.log(row_id);
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
   $xx = oval.index_name;
    if(oval.index_name != null){
      if(typeof oval != null || typeof oval != "undefined") {
        $xx = oval.index_name.replace(/\s+/g, '_').toLowerCase();
      } 
    }
    tableColumnData.push({ data: $xx });
  });

  var table = $('.table').DataTable({
    searching: true,
    paging: true,
    pagingType: "full_numbers",
    info: true,
    responsive: true,
    columnDefs: [
      {
        targets: 0,
        render: function (data, type, row) {
          return '<span>' + data + '</span>'
        }
      }
    ],
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
      processing: '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>'
    },
    serverSide: true,
    serverMethod: 'post',
    ajax: {
      url: '/shipment/shipmentData/' + user_id + "/" + role_id,
      data: function (d) {
        if(d.post_trigger !== "") {
          d.shipment_id = $("input[name='shipment_id']").val();
          d.console_id = $("input[name='console_id']").val();
          d.ETA = $("input[name='ETA']").val();
          d.ETD = $("input[name='ETD']").val();
          d.consignee = $("input[name='consignee']").val();
          d.consignor = $("input[name='consignor']").val();
          d.master_bill = $("input[name='master_bill']").val();
          d.house_bill = $("input[name='house_bill']").val();
          d.container = $("input[name='container']").val();
          d.container_mode = $("#container_mode").val();
          d.transportmode_sea = $("input[name='transportmode_sea']:checked").val();
          d.transportmode_air = $("input[name='transportmode_air']:checked").val();
          d.post_trigger = $("input[name='post_trigger']").val();
        }
      },
    },
    columns: tableColumnData,
    rowId: 'real_id_shipment',
    select: true,
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
    initComplete: setColor,
    "fnPreDrawCallback": function (oSettings) {
      // if ($('#test').val() == 1) {
      //   return false;
      // }
      setColor();
    }
  })
  .on('draw.dt', function () {

    var info = table.page.info();
    console.log('PAGE: ' + info.page);
    setColor();
    // $('#pageInfo').html('Showing page: ' + info.page + ' of ' + info.pages);
  });

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
      format: 'DD/MM/YYYY'
    }
  });

  //on hide calendar set start date and end date
  $('input[name="ETA"], input[name="ETD"]').on('apply.daterangepicker', function (ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
  });

  //for advance search.
  $("#advance-search-btn").on("click", function () {
    var check_arr = [];
    $("input[name='post_trigger']").val("set");
    $("input:checkbox[name=stat]:checked").each(function () {
      check_arr.push($(this).val());
    });
    $("#status").val(check_arr);

    var data = $("#addvance-search-form").serializeArray();

    var query_data = {};
    console.log(query_data);
    var html = "";
    $.each(data, function (key, value) {
      var index = value.name;
      var val = value.value;
      query_data[index] = val;
    });
    // $("input[name='origin']").val(query_data.origin);
    // $("input[name='transportmode']").val(query_data.transportmode);
    table.ajax.reload(setColor);
    //table.ajax.reload();
    //table.draw();
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
    nonSelectedListLabel: 'Non-selected Settings',
    selectedListLabel: 'Selected Settings',
    preserveSelectionOnMove: 'all',
    helperSelectNamePostfix: '_helper',
    sortByInputOrder: false,
    filterTextClear: ""
  });

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
          console.log('test tes');
           console.log(Sdata);
           console.log(res);
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
      console.log("last -- " + last);
      byLevel();
    }, 100);


  }).find('option:selected').map(function () { return this.value }).get();

  var check_arr = [];
  function getSettings(map) {
    var Sdata = [];
    var opt_value = [];
    var temp = [];
    $('#bootstrap-duallistbox-selected-list_settings-dual  option').each(function () {
      console.log($(this).data('text'));

      opt_value.push($(this).val());
      Sdata.push({ index_name: $(this).data('text'), index_value: $(this).val(), index_check: true, index_lvl: $(this).attr('lvl') });
    });

    $('#bootstrap-duallistbox-nonselected-list_settings-dual  option').each(function () {
      opt_value.push($(this).val());
      Sdata.push({ index_name: $(this).data('text'), index_value: $(this).val(), index_check: false, index_lvl: $(this).attr('lvl') });
    });

    if (check_arr.length != Sdata.length && check_arr.length != 0) {
      $.each(check_arr, function (k, v) {
        if ($.inArray(v.index_value, opt_value) == -1) {
          console.log("from the is array");
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

    //console.log(Sdata);
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
          // for checking only
          console.log('test tes');
           console.log(Sdata);
           console.log(res);
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
        // for checking only
        console.log(res);
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

  $('.js-example-basic-multiple').select2();
  $('.js-example-basic-multiple').on("select2:select", function (e) {
    var data = e.params.data.text;

    if (data == 'Select All') {
      $(".js-example-basic-multiple > option").prop("selected", "selected");

      $(".js-example-basic-multiple > option").each(function (key, val) {
        console.log($(val).val());
        if ($(val).val() == 'all') {
          $(this).prop('selected', false);
        }
      });

      $(".js-example-basic-multiple").trigger("change");
    }
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
  console.log(level);

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
  var url = "shipment/info/101/" + shipment_num;

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
