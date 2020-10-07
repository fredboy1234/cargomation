<?php
include("connection.php");
session_start(); 
if(isset($_SESSION['PIDclient'])){
$x_key =  $_SESSION['PIDclient'];
$location = file_get_contents('https://ipapi.co/city/');
$link = file_get_contents("https://api.myip.com/");
$data1 = json_decode($link);
$ip = $data1->ip;
$country = $data1->country;
$url="http://api.openweathermap.org/data/2.5/forecast?q=".$location.",cel&APPID=a890cab96b0d96bfe2f6959725a12239&units=metric";
$json=file_get_contents($url);
$data=json_decode($json);
$location = $data->city->name." ".$data->city->country;
$temp  =  $data->list[0]->main->temp;
$humid =  $data->list[0]->main->humidity."%";
//echo  $data->list[0]->weather[0]->description;
//$data->list[0]->dt_txt;
//echo $dayOfWeek = date("l", strtotime($data->list[22]->dt_txt));


}
else
{
	
echo "<script>
alert('Session got expired !!');
window.location.href='/login';
</script>";	
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>A2B FREIGHT HUB</title>
    <link href="dist/css/style.min.css" rel="stylesheet">
	<link href="dist/css/pages/floating-label.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
	<link href="dropzone/dist/min/dropzone.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/colreorder/1.5.2/css/colReorder.dataTables.min.css" rel="stylesheet">
	<style>
	.swal2-popup {
	font-size: 0.90em !important;
	}
	table.dataTable.display tbody tr.odd {
    background-color: #151414;
	}
	table.dataTable.display tbody tr.even {
    background-color: #151414;
	}
	table.dataTable.display tbody tr.odd > .sorting_1, table.dataTable.order-column.stripe tbody tr.odd > .sorting_1 {
    background-color: #2b2727;
	}
	table.dataTable.display tbody tr.even > .sorting_1, table.dataTable.order-column.stripe tbody tr.even > .sorting_1 {
    background-color: #2b2727;
	}
	table.dataTable tbody tr {
    background-color: #575454;
	}
	.dataTables_processing, .dataTables_wrapper .dataTables_paginate {
    color: #fdfdfd;
	}
	.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate {
		color: #fff;
	}
	.dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
    cursor: default;
    color: #fff !important;
    border: 1px solid transparent;
    background: #ff262600;
    box-shadow: none;
	}
	.dataTables_wrapper .dataTables_paginate .paginate_button {
	
	color: #fff !important;
	}
	.modal-body {
    max-height:1000px; 
	height:750px;
    overflow-y: auto;
}
	</style>
</head>

<body class="skin-default-dark fixed-layout" onClose="reqLogout()">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">a2b freight hub...</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <?php include("inc.headerclient.php");?>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">My Dashboard</h4>
                    </div>
                </div>
				
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="sendfileuser"></div>		
                            </div>
							
                        </div>
                    </div>
                </div>    
				
				
                <!-- .right-sidebar -->
                <?php include('side_barclient.php'); ?>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>

        <footer class="footer">
            © 2020 A2BFREIGHTHUB by a2bsolutiongroup
        </footer>
    </div>
	<?php include('inc.scripts.php');?>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/colreorder/1.5.2/js/dataTables.colReorder.min.js"></script>

<?php include('global_functionclient.php');?>
	
	
</body>
</html>