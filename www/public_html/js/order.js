$(document).ready(function() {

/*default initialization*/
loader('#myTable1');
/*end of default initialization*/

/*call nav-tab recalculate datatable*/
$(document).on('shown.bs.tab', 'a[data-toggle="pill"]', function (e) 
{
recalculateDataTableResponsiveSize() ; 
});

/*click handler*/
$('ul.nav').on('click', '.nav-item:contains("Confirmed")', function(e){loader('#myTable2');$('#myTable2').DataTable().search( 'CTN' ).draw();});
$('ul.nav').on('click', '.nav-item:contains("Shipped")', function(e){loader('#myTable3');$('#myTable3').DataTable().search( 'PLT' ).draw();});
/*end click handler*/


/*function handle json data*/
function loader(var_tbl) { 
            if (! $.fn.DataTable.isDataTable(var_tbl)) {
                $(var_tbl).DataTable({
                    responsive: true,
                    "autoWidth": true,
                    ajax:           '../settings/orders.json',
                    "columns": [
                            { "data": "1"},{ "data": "2"},{ "data": "3"},{ "data": "4"},{ "data": "5"},
                            { "data": "6"},{ "data": "7"},{ "data": "8"},{ "data": "9"},{ "data": "10"},
                            { "data": "11"},{ "data": "12"},{ "data": "13"},{ "data": "14"},{ "data": "15"},
                            { "data": "16"},{ "data": "17"},{ "data": "18"},{ "data": "19"},{ "data": "20"},
                            { "data": "21"},{ "data": "22"}
                        ],
                        paging:true
                });
                 recalculateDataTableResponsiveSize() ;  
                
            } else
         {  
            console.log('table has been initialized.'+var_tbl)
    }
}

/* Recalculates the size of the responsive DataTable */
function recalculateDataTableResponsiveSize() {
    $($.fn.dataTable.tables(true)).DataTable().responsive.recalc();
}

  
});
