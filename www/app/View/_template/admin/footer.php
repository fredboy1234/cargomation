  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2019-2020 <a href="/">A2BFreightHub.com</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.7
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/bower_components/admin-lte/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="/bower_components/admin-lte/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="/bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="/bower_components/admin-lte/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="/bower_components/admin-lte/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="/bower_components/admin-lte/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="/bower_components/admin-lte/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="/bower_components/admin-lte/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="/bower_components/admin-lte/plugins/moment/moment.min.js"></script>
<script src="/bower_components/admin-lte/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="/bower_components/admin-lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="/bower_components/admin-lte/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="/bower_components/admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="/bower_components/admin-lte/dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="/bower_components/admin-lte/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/bower_components/admin-lte/dist/js/demo.js"></script>
<script src="/bower_components/admin-lte/plugins/select2/js/select2.full.min.js"></script>
<!-- Data Table -->
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script>
  //JS script
  var _url = document.domain;

  $(document).on('click','.doc', function(e){
    e.preventDefault(); var type = "";
    if ($(this).data('type')) { type = "/" + $(this).data('type'); }
    $('#myModal').modal('show').find('.modal-body').load("/admin/document/" + $(this).data('id') + type);
  });
  $('#myModal').on('hidden.bs.modal', function (e){ 
    $('#myModal .modal-body').empty().append('Loading...');
  });
  var table = $('.table').DataTable({
    searching: true, 
    paging: false, 
    info: false,
    bFilter: false,
    sDom: 'lrtip'
  });

  $('#doc_search').on( 'keyup', function () {
    table.search( this.value ).draw();
  });
  
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

  $('input[name="ETA"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  //for advance search.
  $("#advance-search-btn").on("click",function(){
    var data = $("#addvance-search-form").serializeArray();
    var query_data = {};
    var html="";
    $.each(data,function(key,value){
      var index = value.name;
      var val = value.value;
      query_data[index] = val;
    });
    console.log(query_data);
    $.ajax({
      type: "POST",
      url: "/admin/advanceSearch/",
      ContentType: 'application/json',
      data: query_data,
      beforeSend: function(){
        $("table tbody").html('<tr class="odd"><td valign="top" colspan="9" class="dataTables_empty">Loading...</td></tr>');
      },
      success: function(response){
        console.log(response);
       if(response.length > 2){
          $.each(JSON.parse(response),function(key,val){
          var date = new Date(val.etd);
          var newdate= (date.getMonth() + 1) + '/' + date.getDate() + '/' +  date.getFullYear(); 
          html += `<tr>
                    <td>${val.shipment_num}</td>
                    <td>${val.first_name +" "+ val.last_name }</td>
                    <td>${newdate}</td>
                    <td><span class="doc badge badge-success" data-type="HBL" data-id="${val.shipment_num}">Approved</span></td>
                    <td><span class="doc badge badge-success" data-type="CIV" data-id="${val.shipment_num}">Approved</span></td>
                    <td><span class="doc badge badge-warning" data-type="PKL" data-id="${val.shipment_num}">Pending</span></td>
                    <td><span class="doc badge badge-success" data-type="PKD" data-id="${val.shipment_num}">Approved</span></td>
                    <td><span class="doc badge badge-success" data-id="${val.shipment_num}">Approved</span></td>
                    <td>${typeof(val.comment)!="undefined" ? val.comment : "<em>No comment</em>"}</td>
                  </tr>`;
        });
       }else{
         html ='<tr class="odd"><td valign="top" colspan="9" class="dataTables_empty">No matching records found</td></tr>';
       }
       $("table tbody").html(html);
      }
    });
  });
</script>
</body>
</html>