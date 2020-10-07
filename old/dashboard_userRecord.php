<?php
include("connection.php"); 
session_start(); 
if(isset($_SESSION['PID'])){
$x_key =  $_SESSION['PID'];
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
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<style>
	.swal2-popup {
	font-size: 0.90em !important;
	}
	</style>
</head>

<body class="skin-default-blue fixed-layout" onClose="reqLogout()">
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
        <?php include("inc.header.php");?>
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
                        <h4 class="text-themecolor">Dashboard - User Record</h4>
                    </div>
                </div>
		<div class="row">
            <div class="card" style="width:100%;">
                            <div class="card-body">
                                  <div class="table-responsive m-t-40">
                                    <table id="example23"
                                        class="display nowrap table table-hover table-striped table-bordered"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>User ID</th>
                                                <th>Email</th>
                                                <th>Pw Hash</th>
                                                <th>Name</th>
                                                <th>Company Name</th>
                                                <th>Address</th>
                                                <th>Phone #</th>
                                                <th>Tel #</th>
                                                <th>Country</th>
                                                <th>Access Level</th>
                                                <th>Date Registered</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>User ID</th>
                                                <th>Email</th>
                                                <th>Pw Hash</th>
                                                <th>Name</th>
                                                <th>Company Name</th>
                                                <th>Address</th>
                                                <th>Phone #</th>
                                                <th>Tel #</th>
                                                <th>Country</th>
                                                <th>Access Level</th>
                                                <th>Date Registered</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
										<?php
										$sqlUserRecord="
										SELECT
										dbo.tblUser.userID,
										dbo.tblLogin.loginEmail,
										dbo.tblLogin.loginPWHash,
										dbo.tblUser.userFname,
										dbo.tblUser.userLname,
										dbo.tblCompany.companyName,
										dbo.tblCompany.companyAddress,
										dbo.tblCompany.Address_1,
										dbo.tblCompany.City,
										dbo.tblCompany.infoState,
										dbo.tblCompany.postalCode,
										dbo.tblCompany.idCountryCode,
										dbo.tblUser.userPhoneNumber,
										dbo.tblUser.userTelNumber,
										CONVERT(varchar(24),dbo.tblUser.userRegisteredDate) as regDate,
										dbo.tblCountries.CountryName,	
										dbo.tblAccessLevel.userAccessLevel
										
										FROM
										dbo.tblUser
										INNER JOIN dbo.tblLogin
										ON dbo.tblUser.userID = dbo.tblLogin.loginID
										INNER JOIN dbo.tblCompany
										ON dbo.tblUser.userIDCompany = dbo.tblCompany.userIDCompany
										INNER JOIN dbo.tblCountries
										ON dbo.tblUser.userIDCountry = dbo.tblCountries.CountryID
										INNER JOIN dbo.tblAccessLevel
										ON dbo.tblUser.userIDAccessLevel = dbo.tblAccessLevel.userIDAccessLevel";
										$paginateUser = sqlsrv_query( $conn, $sqlUserRecord);
										while( $row = sqlsrv_fetch_array( $paginateUser, SQLSRV_FETCH_ASSOC) ) 
										{
										echo "<tr>
                                                <td>".$row['userID']."</td>
                                                <td>".$row['loginEmail']."</td>
                                                <td>".$row['loginPWHash']."</td>
                                                <td>".$row['userFname']."".$row['userLname']."</td>
                                                <td>".$row['companyName']."</td>
                                                <td>".$row['Address_1']."</td>
                                                <td>".$row['userPhoneNumber']."</td>
                                                <td>".$row['userTelNumber']."</td>
                                                <td>".$row['CountryName']."</td>
                                                <td>".$row['userAccessLevel']."</td>
                                                <td>".$row['regDate']."</td>
                                            </tr>";
										}
										?>
                                          
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>       
		</div>
				


                <!-- .right-sidebar -->
                <?php include('side_bar.php'); ?>
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

    <script src="assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <script src="assets/node_modules/popper/popper.min.js"></script>
    <script src="assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="dist/js/waves.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <script src="assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <script src="dist/js/custom.min.js"></script>
	<script src="assets/node_modules/wizard/jquery.steps.min.js"></script>
    <script src="assets/node_modules/wizard/jquery.validate.min.js"></script>
	<script src="assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
	<script src="assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
	<script src="assets/node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
	<script src="assets/cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="assets/cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="assets/cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="assets/cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="assets/cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="assets/cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="assets/cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
	<script>
		 $(function () {
            $('#myTable').DataTable();
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
                "displayLength": 25,
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                        page: 'current'
                    }).data().each(function (group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                }
            });
            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
            // responsive table
            $('#config-table').DataTable({
                responsive: true
            });
            $('#example23').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
            $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
        });
    </script>
	<?php include('global_function.php');?>
</body>
</html>