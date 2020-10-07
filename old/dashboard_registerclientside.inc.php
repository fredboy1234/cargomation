<?php
include("connection.php"); 
session_start(); 
if(isset($_SESSION['PIDclient'])){
$x_key =  $_SESSION['PIDclient'];
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
                                <h4 class="card-title">Add New Account</h4>
                                <form id="a2b_userRegister" method="post" class="floating-labels m-t-40">
								<div class="row p-t-20">
                                    <div class="form-group col-md-6">
                                        <input type="email" class="form-control" id="pw_email" name="pw_email" onkeyup="isExistClient()" required >
                                        <span class="bar"></span>
                                        <label for="input1">Email Address<b id="isExistClient" style="color:red;font-weight:normal;"></b></label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="password" class="form-control" id="pw_a2b" name="pw_a2b" required>
										
                                        <span class="bar"></span>
                                        <label for="input2">Password <span toggle="#pw_a2b" class="fa fa-fw fa-eye field-icon toggle-password"></span></label>
                                    </div>
								</div>
									<div class="form-group m-b-40">
                                        <select class="form-control p-0" id="company_a2b" name="company_a2b" required >
                                            <option></option>
											<?php
												$sql = "SELECT
												dbo.tblCompany.userIDCompany,
												dbo.tblCompany.companyName
												FROM
												dbo.tblCompany";	
												$sqlCompany = sqlsrv_query( $conn, $sql);
										
												while( $row = sqlsrv_fetch_array( $sqlCompany, SQLSRV_FETCH_ASSOC)) 
												{
												echo "<option value='".$row['userIDCompany']."'>".$row['companyName']."</option>";           		
												}
																					
																					
										?>
                                        </select><span class="bar"></span>
                                        <label for="input6">Company Name&nbsp;<a data-target="#responsive-modal" href="/login" data-toggle="modal"><b data-toggle="tooltip" data-placement="right" title="" data-original-title="Company not found? Register here">[?]</b></a></label>
                                    </div>
									<div class="row p-t-20">
                                    <div class="form-group col-md-6">
                                        <input type="text" class="form-control" id="fname_a2b" name="fname_a2b" required >
                                        <span class="bar"></span>
                                        <label for="input3">First Name</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" class="form-control" id="lname_a2b" name="lname_a2b" required >
                                        <span class="bar"></span>
                                        <label for="input4">Last Name</label>
                                     </div>
									 </div>
									<div class="row p-t-20">
                                    <div class="form-group col-md-6">
                                        <input type="text" class="form-control" id="pnumber_a2b" name="pnumber_a2b" required >
                                        <span class="bar"></span>
                                        <label for="input5">Phone Number</label>
                                    </div>
									<div class="form-group col-md-6">
                                        <input type="text" class="form-control" id="tel_a2b" name="tel_a2b" required >
                                        <span class="bar"></span>
                                        <label for="input5">Tel. Number</label>
                                    </div>
									</div>
									<div class="row p-t-20">
                                    <div class="form-group col-md-6">
                                        <select class="form-control p-0" id="country_a2b" name="country_a2b" required >
                                            <option></option>
											<?php
                                            $sql = "SELECT
											dbo.tblCountries.CountryID,
											dbo.tblCountries.CountryName,
											dbo.tblCountries.TwoCharCountryCode
											FROM
											dbo.tblCountries";	
											$sqlCompany = sqlsrv_query( $conn, $sql);
											while( $row = sqlsrv_fetch_array( $sqlCompany, SQLSRV_FETCH_ASSOC) ) 
													{
													  echo "<option value='".$row['CountryID']."'>".$row['CountryName']." - ".$row['TwoCharCountryCode']."</option>";           		
													}
											?>
                                        </select><span class="bar"></span>
                                        <label for="input6">Country</label>
                                    </div>
									<div class="form-group col-md-6">
                                        <input type="text" class="form-control" id="regdate_a2b" name="regdate_a2b" value="<?php echo date('Y-m-d'); ?>" disabled="disabled" required >
                                        <span class="bar"></span>
                                        <label for="input5">Date of Registration</label>
                                    </div>
									</div>
                                    <div class="form-group m-b-40">
                                        <select class="form-control p-0" id="access_a2b" name="access_a2b" required >
                                            <option></option>
											<?php
                                            $sql = "SELECT
											dbo.tblClientAccessLevel.clientIDAccessLevel,
											dbo.tblClientAccessLevel.clientAccessLevel
											FROM
											dbo.tblClientAccessLevel ORDER BY dbo.tblClientAccessLevel.clientAccessLevel ASC";	
											$sqlCompany = sqlsrv_query( $conn, $sql);
											while( $row = sqlsrv_fetch_array( $sqlCompany, SQLSRV_FETCH_ASSOC) ) 
													{
	
													  echo "<option value='".$row['clientIDAccessLevel']."'>".$row['clientAccessLevel']."</option>";   
															  
													}
											?>
                                        </select><span class="bar"></span>
                                        <label for="input6">User Account Type</label>
                                    </div>
									
									<!--<div class="form-group m-b-40">
                                        <select class="form-control p-0" id="subscribe_a2b" name="subscribe_a2b" required >
                                            <option></option>
											<?php
                                            $sql = "SELECT
											dbo.tblSubscription.subscriptionID,
											dbo.tblSubscription.subscriptionName,
											dbo.tblSubscription.subscriptionUserCapacity
											FROM
											dbo.tblSubscription";	
											$sqlCompany = sqlsrv_query( $conn, $sql);
											while( $row = sqlsrv_fetch_array( $sqlCompany, SQLSRV_FETCH_ASSOC) ) 
													{
													  echo "<option value='".$row['subscriptionID']."'>".$row['subscriptionName']."</option>";           		
													}
											?>
                                        </select><span class="bar"></span>
                                        <label for="input6">Subscription</label>
                                    </div>-->
									
									<div class="form-group m-b-40">
									
									Profile Image: <input id="image-file" name="image-file" type="file" accept="image/x-png,image/gif,image/jpeg" required >
									
									</div>
									<div class="form-group m-b-5">
									<div class="button-group">
										<button type="submit" class="btn waves-effect waves-light btn-lg btn-success">REGISTER</button>
										<button onClick="window.location.reload(false);" type="button" class="btn waves-effect waves-light btn-lg btn-danger">CANCEL</button>
									</div>
                                     </div> 
                                </form>
                            </div>
                        </div>
					</div>
				</div>

				<div id="responsive-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Add New Company</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="a2b_RegisterCompany" method="post">
                                                    <div class="form-group">
                                                        <label for="recipient-name" class="control-label">Company Name:</label>
                                                        <input type="text" class="form-control" id="cName" name="cName">
                                                    </div>
													<div class="form-group">
                                                        <label for="recipient-name" class="control-label">Company Address:</label>
                                                        <input type="text" class="form-control" id="cAddress" name="cAddress">
                                                    </div>
													<div class="form-group">
                                                        <label for="recipient-name" class="control-label">Address_1:</label>
                                                        <input type="text" class="form-control" id="cAddress1" name="cAddress1">
                                                    </div>
													<div class="form-group">
                                                        <label for="recipient-name" class="control-label">City:</label>
                                                        <input type="text" class="form-control" id="cCity" name="cCity">
                                                    </div>
													<div class="form-group">
                                                        <label for="recipient-name" class="control-label">State:</label>
                                                        <input type="text" class="form-control" id="cState" name="cState">
                                                    </div>
													<div class="form-group">
                                                        <label for="recipient-name" class="control-label">Postal Code:</label>
                                                        <input type="text" class="form-control" id="cPostal" name="cPostal">
                                                    </div>
													<div class="form-group">
                                                        <label for="recipient-name" class="control-label">Country:</label>
                                                         <select class="form-control p-0" id="cCountry" name="cCountry">
                                            <option></option>
											<?php
                                            $sql = "SELECT
											dbo.tblCountries.CountryID,
											dbo.tblCountries.CountryName,
											dbo.tblCountries.TwoCharCountryCode
											FROM
											dbo.tblCountries";	
											$sqlCompany = sqlsrv_query( $conn, $sql);
											while( $row = sqlsrv_fetch_array( $sqlCompany, SQLSRV_FETCH_ASSOC) ) 
													{
													  echo "<option value='".$row['CountryID']."'>".$row['CountryName']." - ".$row['TwoCharCountryCode']."</option>";           		
													}
											?>
                                        </select>
                                                    </div>
													
													 <div class="modal-footer">
                                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger waves-effect waves-light">Save changes</button>
                                            </div>
                                                </form>
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
	<script>
  $( "#a2b_userRegister" ).submit(function(e) {
e.preventDefault();
Swal.fire({
  title: 'Are you sure?',
  text: "Do you want to continue",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, continue!'
}).then((result) => {
  if (result.value) {
	  $.ajax({
					url:"functions/reg_log_authClient.php",
					method:"POST",
					data:new FormData(this),
					contentType:false,
					processData:false,
					success:function(data){ 
												
						if(data == 'z!kk]XmzA`]-a28K'){	
						 Swal.fire(
									'',
									'Registration completed successfully !',
									'success'
									)
						setTimeout(function() { 
						window.location.href = "/user_auth";
						}, 3000);
									 
						}
						else if(data == '1'){
							Swal.fire(
						'Registration failed!',
						'Email address is already taken !!',
						'error'
						)
						setTimeout(function() { 
						window.location.href = "/user_auth";
						}, 3000);
							
						}
						else if(data == 'z!kk]XmzA'){
							Swal.fire(
						'Registration failed!',
						'Maximum users has been reached. Please upgrade your subscription',
						'error'
						)
						setTimeout(function() { 
						window.location.href = "/user_auth";
						}, 3000);
							
						}
						else{
					    alert(data);
						Swal.fire(
						'Oops..',
						'Something went wrong with the transaction.',
						'error'
						)
						setTimeout(function() { 
						window.location.href = "/user_auth";
						}, 3000);						
						}
					}
				});

  }
  else{
	Swal.fire(
      'Cancelled!',
      'transaction has been cancelled.',
      'warning'
    )  
  }
})	
});

  $("#a2b_RegisterCompany" ).submit(function(e) {
	  e.preventDefault();
Swal.fire({
  title: 'Are you sure?',
  text: "Do you want to continue",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, continue!'
}).then((result) => {
  if (result.value) {
	  $.ajax({
					url:"functions/reg_company_auth.php",
					method:"POST",
					data:new FormData(this),
					contentType:false,
					processData:false,
					success:function(data){ 
												
						if(data == 'z!kk`]-a28K]XmzA'){	
						 Swal.fire(
									'',
									'Registration completed successfully !',
									'success'
									)
						setTimeout(function() { 
						window.location.href = "/add_client";
						}, 3000);
									 
						}
						else if(data == '1'){
							Swal.fire(
						'Registration failed!',
						'Email address is already taken !!',
						'error'
						)
						setTimeout(function() { 
						window.location.href = "/add_client";
						}, 3000);
							
						}
						else{
					    alert(data);
						Swal.fire(
						'Oops..',
						'Something went wrong with the transaction.',
						'error'
						)
						setTimeout(function() { 
						window.location.href = "/add_client";
						}, 3000);						
						}
					}
				});

  }
  else{
	Swal.fire(
      'Cancelled!',
      'transaction has been cancelled.',
      'warning'
    )  
  }
})

});

$(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
 </script>
<?php include('global_functionclient.php');?>
</body>
</html>