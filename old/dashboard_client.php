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

												    $getSubscription = "SELECT
													dbo.tblSubscription.subscriptionID,
													dbo.tblSubscription.subscriptionName,
													dbo.tblSubscription.subscriptionUserCapacity
													FROM
													dbo.tblSubscription
													WHERE
													dbo.tblSubscription.subscriptionID =".$_SESSION['SUBID']."";
																$checkIDSubscription = sqlsrv_query($conn, $getSubscription);
																while ($rowSub = sqlsrv_fetch_array($checkIDSubscription, SQLSRV_FETCH_ASSOC))
																{
																	$subscription = $rowSub['subscriptionName'];
																	$capacity = $rowSub['subscriptionUserCapacity'];
																}
											
													$sqlCountUser = "SELECT
													count(dbo.tblClientDetails.detailclientID) as ctrRemain
													FROM
													dbo.tblClientDetails
													WHERE
													dbo.tblClientDetails.detailclientID = " . $_SESSION['PIDclient'] . "
													GROUP BY
													dbo.tblClientDetails.detailclientID";
													$checkCountRemain = sqlsrv_query($conn, $sqlCountUser);
								
													$ifCounterExist = sqlsrv_has_rows($checkCountRemain);
													
													if($ifCounterExist === true){
													
													while ($row_count = sqlsrv_fetch_array($checkCountRemain, SQLSRV_FETCH_ASSOC))
													{
														$ctruser = $row_count['ctrRemain'];
														$remaining = (int)$_SESSION['SUBSCRIPTIONCAPACITY'] - (int)$row_count['ctrRemain'];
								
													}}
													else{
													$ctruser= 0;
													$remaining = $subscription;
													}
												

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
	<style>
	.swal2-popup {
	font-size: 0.90em !important;
	}
	.legendLabel
{
    color:White;
}
legend: {        
    labelBoxBorderColor: "#fff"
}
 
grid: {                
    backgroundColor: "#000000",
    tickColor: "#008040"
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
				
                <div class="card-group">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="d-flex no-block align-items-center">
										<div>
											<h3><i class="icon-screen-desktop"></i></h3>
											<p class="text-muted">NO. OF USERS</p>
										</div>
										<div class="ml-auto">
											<h2 class="counter text-primary">
											<?php
											$sqlUserCount="SELECT
											count(tblUser.userID) as ctrUser
											FROM
											dbo.tblUser";
											$sqlCtr = sqlsrv_query( $conn, $sqlUserCount);
											while( $row = sqlsrv_fetch_array( $sqlCtr, SQLSRV_FETCH_ASSOC) ) 
											{
												echo $ctr = $row['ctrUser'];
											}
											?>
											</h2>
											
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="progress">
										<div class="progress-bar bg-primary" role="progressbar" style="width:<?php echo ($ctr/100)*100;?>%; height: 6px;" aria-valuenow="1" aria-valuemin="222" aria-valuemax="100"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Column -->
					<!-- Column -->
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="d-flex no-block align-items-center">
										<div>
											<h3><i class="icon-note"></i></h3>
											<p class="text-muted">TOTAL FILES UPLOADED</p>
										</div>
										<div class="ml-auto">
											<h2 class="counter text-cyan">0</h2>
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="progress">
										<div class="progress-bar bg-cyan" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Column -->
					<!-- Column -->
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="d-flex no-block align-items-center nav-item right-side-toggle">
										<div>
											<h3><i class="icon-user"></i></h3>
											<p class="text-muted">ONLINE USERS</p>
										</div>
										<div class="ml-auto">
											<h2 class="counter text-purple">
											<div id="olUsers"></div>		
											</h2>
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="progress">
										<div class="progress-bar bg-purple" role="progressbar" style="width:55%; height: 2px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Column -->
					<!-- Column -->
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="d-flex no-block align-items-center">
										<div>
											<h3><i class="icon-bag"></i></h3>
											<p class="text-muted">TICKETS</p>
										</div>
										<div class="ml-auto">
											<h2 class="counter text-success">0</h2>
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="progress">
										<div class="progress-bar bg-success" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>    
				
				<div class="row">
                    <!-- Column -->
                    <div class="col-lg-4">
                        <div class="card">
                            
                            <div class="card-body bg-light">
                                <div class="d-flex no-block align-items-center">
                                    <span><h2 class=""><?php

										$date = date("h:i:sa");
										$dayOfWeek = date("l", strtotime($date));
										echo $dayOfWeek;
									
									?></h2><small><?php echo $date;?></small></span>
                                    <div class="ml-auto">
                                        <canvas class="<?php echo $data->list[0]->weather[0]->description; ?>" width="44" height="44"></canvas> <span class="display-6"><?php echo $temp;?><sup>°C</sup></span> </div>
                                </div>
                            </div>
							<div class="card-body">
                                <h5 class="card-title m-b-0">ACCOUNT INFORMATION</h5>
                            </div>
                            <div class="card-body">
                                <div class="row pb-1">
                                    <div class="col-md-8">
									<div class="row">
                                            <div class="col-6 py-2">
                                                <span>Name</span>
                                            </div>
                                            <div class="col-6 py-2">
                                                <span class="font-medium"><?php echo $_SESSION['FNAME']; ?></span>
                                            </div>
                                       </div>
									   <div class="row">
                                            <div class="col-6 py-2">
                                                <span>Phone Number</span>
                                            </div>
                                            <div class="col-6 py-2">
                                                <span class="font-medium"><?php echo $_SESSION['NUMBERDETAILS'];?></span>
                                            </div>
                                       </div>
									<div class="row">
                                            <div class="col-6 py-2">
                                                <span>Subscription</span>
                                            </div>
                                            <div class="col-6 py-2">
                                                <span class="font-medium"><?php echo $_SESSION['SUBSCRIPTION'];?></span>
                                            </div>
                                       </div>
									   <div class="row">
                                            <div class="col-6 py-2">
                                                <span>Max Users</span>
                                            </div>
                                            <div class="col-6 py-2">
                                                <span class="font-medium"><?php echo $_SESSION['SUBSCRIPTIONCAPACITY'];?></span>
                                            </div>
                                       </div>
									   <div class="row">
                                            <div class="col-6 py-2">
                                                <span>Created Users</span>
                                            </div>
                                            <div class="col-6 py-2">
                                                <span class="font-medium"><?php echo $ctruser; ?></span>
                                            </div>
                                       </div>
									   <div class="row">
                                            <div class="col-6 py-2">
                                                <span>Remaining Users</span>
                                            </div>
                                            <div class="col-6 py-2">
                                                <span class="font-medium"><?php echo $remaining;?></span></span>
                                            </div>
                                       </div>
									<!--<div class="row">
                                            <div class="col-6 py-2">
                                                <span>Country</span>
                                            </div>
                                            <div class="col-6 py-2">
                                                <span class="font-medium"><?php echo $country; ?></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 py-2">
                                                <span>Location</span>
                                            </div>
                                            <div class="col-6 py-2 text-truncate">
                                                <span class="font-medium"><?php echo $location; ?></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 py-2">
                                                <span>IP Address</span>
                                            </div>
                                            <div class="col-6 py-2">
                                                <span class="font-medium"><?php echo $ip; ?></span>
                                            </div>
                                        </div>-->
                                        
                                    </div>
                                    
                                </div>
                                <div class="row mt-4 pt-3">
                               
                                    <!--<div class="col-lg-2 col-md-4 col-4 text-center">
                                        <h5>Tue</h5>
                                        <div class="m-t-10 m-b-10">
                                            <canvas class="<?php //echo $data->list[0]->weather[0]->description; ?>" width="30" height="30"></canvas>
                                        </div>
                                        <h6><?php //echo round($data->list[18]->main->temp,1)."°F";?></h6>
                                    </div>
                                   
                                    <div class="col-lg-2 col-md-4 col-4 text-center">
                                        <h5 class="text-nowrap">Wed</h5>
                                        <div class="m-t-10 m-b-10">
                                            <canvas class="<?php //echo $data->list[1]->weather[0]->description[0]; ?>" width="30" height="30"></canvas>
                                        </div>
                                        <h6><?php //echo round($data->list[19]->main->temp,1)."°F";?></h6>
                                    </div>
                                 
                                    <div class="col-lg-2 col-md-4 col-4 text-center">
                                        <h5>Thu</h5>
                                        <div class="m-t-10 m-b-10">
                                            <canvas class="<?php //echo $data->list[2]->weather[0]->description; ?>" width="30" height="30"></canvas>
                                        </div>
                                        <h6><?php //echo round($data->list[20]->main->temp,1)."°F";?></h6>
                                    </div>
                                   
                                    <div class="col-lg-2 col-md-4 col-4 text-center">
                                        <h5>Fri</h5>
                                        <div class="m-t-10 m-b-10">
                                            <canvas class="<?php //echo $data->list[3]->weather[0]->description; ?>" width="30" height="30"></canvas>
                                        </div>
                                        <h6><?php //echo round($data->list[21]->main->temp,1)."°F";?></h6>
                                    </div>
                                   
                                    <div class="col-lg-2 col-md-4 col-4 text-center">
                                        <h5>Sat</h5>
                                        <div class="m-t-10 m-b-10">
                                            <canvas class="<?php //echo $data->list[4]->weather[0]->description; ?>" width="30" height="30"></canvas>
                                        </div>
                                        <h6><?php //echo round($data->list[22]->main->temp,1)."°F";?></h6>
                                    </div>
                                   
                                    <div class="col-lg-2 col-md-4 col-4 text-center">
                                        <h5>Sun</h5>
                                        <div class="m-t-10 m-b-10">
                                            <canvas class="<?php //echo $data->list[5]->weather[0]->description ?>" width="30" height="30"></canvas>
                                        </div>
                                        <h6><?php //echo round($data->list[23]->main->temp,1)."°F";?></h6>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    
                    <!-- Column -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body" >
                                <h5 class="card-title">USER LOGIN HISTORY</h5>
                                <ul class="feeds mt-3">
									<div id="id_log"></div>
                                </ul>
                            </div>
                        </div>
                    </div>
					 <?php 
								$bytes = disk_free_space("C:");
								$si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
								$base = 1024;
								$class = min((int)log($bytes , $base) , count($si_prefix) - 1);
								$usage = sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class] . '<br />';
								$bytes1 = disk_total_space("C:");
								$si_prefix1 = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
								$base1 = 1024;
								$class1 = min((int)log($bytes1 , $base1) , count($si_prefix1) - 1);
								$totalspace = sprintf('%1.2f' , $bytes1 / pow($base1,$class1)) . ' ' . $si_prefix1[$class1] . '<br />';
			 										
					?>
					   <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">CPU LOAD</h5>

                                <div class="stats-row m-t-20 m-b-20">
                                    <div class="stat-item">
                                        <h6>Total Usage</h6> <b><?php echo $usage; ?></b></div>
                                    <div class="stat-item">
                                        <h6>Space</h6> <b><?php echo $totalspace; ?> </b></div>
                                    <div class="stat-item">
                                        <h6>CPU</h6> <b><?php echo(rand(5,30))."%"; ?></b></div>
                                </div>
                                <div>
                                   
									<div class="row">
										<div class="col-md-12 ">
											<strong></strong>
											<div id="flot-placeholder1" style="height:300px"></div>                     
										</div>
									</div>
									
                                </div>
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
 
	<?php
	include('inc.scripts.php');
	include('global_functionclient.php');
	//include('auth_setters.php');
	?>
	
	
</body>
</html>