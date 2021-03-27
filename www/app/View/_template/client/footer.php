  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2019-2020 <a href="/"><?=APP_NAME?></a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <a href="/contact">Contact Us @support@cargomation.com</a>
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
<!-- jquery-validation -->
<script src="/bower_components/admin-lte/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="/bower_components/admin-lte/plugins/jquery-validation/additional-methods.min.js"></script>
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
<!-- Selectize JS -->
<script src="/bower_components/selectize/dist/js/standalone/selectize.js"></script>
<!-- DataTables -->
<script src="/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-select/js/select.bootstrap4.min.js"></script>
<!-- Bootstrap File Input -->
<script src="/bower_components/bootstrap-fileinput/js/plugins/piexif.js" type="text/javascript"></script>
<script src="/bower_components/bootstrap-fileinput/js/plugins/sortable.js" type="text/javascript"></script>
<script src="/bower_components/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>
<script src="/bower_components/bootstrap-fileinput/js/locales/fr.js" type="text/javascript"></script>
<script src="/bower_components/bootstrap-fileinput/js/locales/es.js" type="text/javascript"></script>
<script src="/bower_components/bootstrap-fileinput/themes/fas/theme.js" type="text/javascript"></script>
<script src="/bower_components/bootstrap-fileinput/themes/explorer-fas/theme.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/colreorder/1.5.3/js/dataTables.colReorder.min.js" type="text/javascript"></script>
<!--for 3rd party-->
<!-- <script src="https://cdn.datatables.net/colreorder/1.5.3/js/dataTables.colReorder.min.js" type="text/javascript"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap4-duallistbox/4.0.2/jquery.bootstrap-duallistbox.min.js"></script>
<!-- Custome JS -->
<?= $this->getJS(); ?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<!-- Sweetalert -->
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<!-- Bootstrap Sweetalert -->
<!-- <script src="/bower_components/bootstrap-sweetalert/dist/sweetalert.min.js"></script> -->
<!-- SweetAlert2 -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
   <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
   <script src="https://cdn.jsdelivr.net/npm/Leaflet-MovingMaker@0.0.1/MovingMarker.js"></script>
   <script src="https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.umd.js"></script>

</body>
</html>

