$(document).ready(function() {

/*default initialization*/
//loader('#myTable1');
/*end of default initialization*/

/*call nav-tab recalculate datatable*/
$(document).on('shown.bs.tab', 'a[data-toggle="pill"]', function (e) 
{
    //recalculateDataTableResponsiveSize() ; 
});

/*click handler*/
//$('ul.nav').on('click', '.nav-item:contains("Confirmed")', function(e){loader('#myTable2');$('#myTable2').DataTable().search( 'CTN' ).draw();});
//$('ul.nav').on('click', '.nav-item:contains("Shipped")', function(e){loader('#myTable3');$('#myTable3').DataTable().search( 'PLT' ).draw();});
/*end click handler*/


/*function handle json data*/
// function loader(var_tbl) { 
//             if (! $.fn.DataTable.isDataTable(var_tbl)) {
//                 $(var_tbl).DataTable({
//                     responsive: true,
//                     "autoWidth": true,
//                     ajax:           '../settings/orders.json',
//                     "columns": [
//                             { "data": "1"},{ "data": "2"},{ "data": "3"},{ "data": "4"},{ "data": "5"},
//                             { "data": "6"},{ "data": "7"},{ "data": "8"},{ "data": "9"},{ "data": "10"},
//                             { "data": "11"},{ "data": "12"},{ "data": "13"},{ "data": "14"},{ "data": "15"},
//                             { "data": "16"},{ "data": "17"},{ "data": "18"},{ "data": "19"},{ "data": "20"},
//                             { "data": "21"},{ "data": "22"}
//                         ],
//                         paging:true
//                 });
//                  recalculateDataTableResponsiveSize() ;  
                
//             } else
//          {  
//             console.log('table has been initialized.'+var_tbl)
//     }
// }

/* Recalculates the size of the responsive DataTable */
// function recalculateDataTableResponsiveSize() {
//     $($.fn.dataTable.tables(true)).DataTable().responsive.recalc();
// }

var table = $('#myTable1').DataTable( {
    // "serverSide": true,
    "autoWidth": false,
    "ajax": document.location.origin+"/order/orderData/",
    "ordering": true,
    "bPaginate":true,
    "responsive:":true,
    "sPaginationType":"full_numbers",
    "iDisplayLength": 8,
    "columns": [
        { "data": "order_number" },
        { "data": "order_date" },
        { "data": "status" },
        { "data": "buyer" },
        { "data": "supplier" },
        { "data": "transport_mode" },
        { "data": "goods_origin" },
        { "data": "good_destination" },
        { "data": "load_port" },
        { "data": "discharge_port" },
        { "data": "packs" },
        { "data": "type" },
        { "data": "volume" },
        { "data": "weight" },
        { "data": "uw" },
        //{ "data": "req_stock" },
        //{ "data": "req_work" }, 
        { "data": "order_line" },     
    ],
    "order": [[1, 'desc']],  
});

$(".filter-order").on('click',function(){
    var status = $(this).attr('data-fil');
   
    if(status === "all"){
        table.ajax.reload(); 
    }else{
        $('#myTable2').DataTable().clear();
        $('#myTable2').dataTable().fnDestroy();
        var filterTable = $('#myTable2').DataTable( {
            "autoWidth": false,
            "scrollX": true,
            "scrollY":        "250px",
            "fixedColumns": false,
            "ajax": {
                url: document.location.origin + '/order/getFilterResults/',
                type: "POST",
                data:{status:status},
            },
            "ordering": true,
            "bPaginate":true,
            "responsive:":true,
            "sPaginationType":"full_numbers",
            "iDisplayLength": 8,
            "columns": [
                { "data": "order_number" },
                { "data": "order_date" },
                { "data": "status" },
                { "data": "buyer" },
                { "data": "supplier" },
                { "data": "transport_mode" },
                { "data": "goods_origin" },
                { "data": "good_destination" },
                { "data": "load_port" },
                { "data": "discharge_port" },
                { "data": "packs" },
                { "data": "type" },
                { "data": "volume" },
                { "data": "weight" },
                { "data": "uw" },
                //{ "data": "req_stock" },
                //{ "data": "req_work" },
                { "data": "order_line" }, 
            ],
            "order": [[1, 'desc']],  
        });
        // $.ajax({
        //     url: document.location.origin + '/order/getFilterResults/',
        //     type: "POST",
        //     dataType: "json",
        //     data:{status:status},
        //     success: function (data) {
        //     }
        // }); 
    }
   
});

});

//filter count 
$.ajax({
    url: document.location.origin + '/order/getCountsForFilterButton/',
    type: "POST",
    dataType: "json",
    //data:{userid:uid,rolename:rolename},
    success: function (data) {
        $.each(data,function(okey,oval){
            console.log(okey);
            $("."+okey+"_fil span").text(oval);
        });
    }
}); 

$(function () {
  $('[data-toggle="popover"]').popover()
})
