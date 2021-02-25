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
    }); 
});