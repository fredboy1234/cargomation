<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $this->escapeHTML($this->title . " - " . APP_NAME); ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/bower_components/admin-lte/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Selectize CSS -->
  <!-- <link rel="stylesheet" href="/bower_components/selectize/dist/css/selectize.css"> -->
  <!-- <link rel="stylesheet" href="/bower_components/selectize/dist/css/selectize.legacy.css"> -->
  <!-- <link rel="stylesheet" href="/bower_components/selectize/dist/css/selectize.default.css"> -->
  <!-- <link rel="stylesheet" href="/bower_components/selectize/dist/css/selectize.bootstrap4.css"> -->
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/datatables-select/css/select.bootstrap4.min.css">
  <!-- Bootstrap File Input -->
  <link href="/bower_components/bootstrap-fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
  <!-- Custom Style-->
  <!-- <link rel="stylesheet" href = "/css/style.css"> -->
  <!-- 
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">
  <link href="/bower_components/bootstrap-fileinput/themes/explorer-fas/theme.css" media="all" rel="stylesheet" type="text/css"/>
  -->
  <!-- Bootstrap Sweetalert -->
  <!-- <link rel="stylesheet" href="/bower_components/bootstrap-sweetalert/dist/sweetalert.css"> -->
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-default/default.css" id="theme-styles">
  <!-- Bootstrap Stepper -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- CSS -->
  <?= $this->getCSS(); ?>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed text-sm">
  <div class="wrapper">
  
  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center" style="background-color: #0f1255;">
    <img class="animation__shake" src="/img/logo.png" alt="CargoMationLogo" height="80" width="80">
  </div>
