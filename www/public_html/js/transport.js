$(document).ready(function(){
    
    var tableColumnData = [];
    $.each(userData,function(okey,oval){
      tableColumnData.push({data:oval.index_name.replace(/\s+/g, '_').toLowerCase()});
    });
    console.log(tableColumnData);
    var table = $('.table').DataTable({
      searching: true, 
      paging: false, 
      info: false,
      responsive: true,
      autoWidth: false,
      lengthChange: false,
      colReorder:true,
      processing: true,
      language: {
        processing: '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>'
      },
      serverMethod: 'post',
      ajax: {
        url: document.location.origin + '/transport/transportSSR/',
        data:function(d){
            d.shipment_id = $("input[name='transport_id']").val();
            d.shipment_num = $("input[name='shipment_num']").val();
            d.ETA = $("input[name='ETA']").val();
            d.client_name = $("input[name='client_name']").val();
            d.vessel_name = $("input[name='vessel_name']").val();
            d.actual_full_deliver = $("input[name='actual_full_deliver']").val();
            d.container = $("input[name='container']").val();
            d.trans_estimated_delivery = $("input[name='trans_estimated_delivery']").val();
            d.fcl_unload = $("input[name='fcl_unload']").val();
            // d.origin = $("input[name='origin']:checked").val();
            // d.status = $("input[name='status']").val();
            // d.transportmode = $("input[name='transportmode']:checked").val();
            d.post_trigger = $("input[name='post_trigger']").val();
          },
       },
      columns: tableColumnData,
    }); 

    $('#doc_search').on( 'keyup', function () {
      table.search( this.value ).draw();
    });

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
      table.ajax.reload();
      //table.draw();
    });

    //reset search
    $("#reset-search").on("click", function () {
      $("#addvance-search-form").find("input[type=text], textarea").val("");
      table.ajax.reload();
    });

    $('#transport-selector').on('change',function(){
      var name = $(this).val();
      $("#daterange").attr("name", name);
    });
    dateshow('input[name="actual_full_deliver"]');
    dateshow('input[name="trans_estimated_delivery"]');
    dateshow('#daterange');

    function dateshow(element){
      $(element).daterangepicker({
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
          format: 'M/DD/YYYY'
        }
      });
      $(element).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
      });
    }
});