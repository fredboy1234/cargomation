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
            d.ETA = $("input[name='ETA']").val();
            d.client_name = $("input[name='client_name']").val();
            d.consignee = $("input[name='vessel_name']").val();
            d.consignor = $("input[name='consignor']").val();
            d.container = $("input[name='container']").val();
            d.origin = $("input[name='origin']:checked").val();
            d.status = $("input[name='status']").val();
            d.transportmode = $("input[name='transportmode']:checked").val();
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
      table.ajax.reload(setColor);
      //table.draw();
    });
});