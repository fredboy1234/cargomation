<?php
include ('../connection.php');
session_start(); 

if(isset($_REQUEST['pw_email']) && isset($_REQUEST['pw_a2b']) && isset($_SESSION['PIDclient'])){

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
	
	
$sql="SELECT
	dbo.tblLoginClient.userclientloginEmail,
	dbo.tblLoginClient.userloginPWHash,
	dbo.tblLoginClient.userclientloginID
	FROM
	dbo.tblLoginClient
	WHERE
	dbo.tblLoginClient.userclientloginEmail =  '".$_REQUEST['pw_email']."'";
	
	$checkID = sqlsrv_query( $conn, $sql);
	
$ifExist = sqlsrv_has_rows ($checkID);
if($ifExist === true)
	{
	echo "1";
	exit();
	}
	else
	{
	if((int)$remaining == 0){
	echo "z!kk]XmzA";
	exit();
	}	
	if(isset($_FILES['image-file'])){
	  date_default_timezone_set('Australia/Melbourne');
	  $date = date('mdYhis');
      $errors= array();
      $file_name = $_FILES['image-file']['name'];
      $file_size =$_FILES['image-file']['size'];
      $file_tmp =$_FILES['image-file']['tmp_name'];
      $file_type=$_FILES['image-file']['type'];
      //$file_ext=strtolower(end(explode('.',$file_name)));
	  
	  $ext = explode('.',strtolower($file_name));
      
      $extensions= array("jpeg","jpg","png");
      
      if(in_array($ext[1],$extensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 9097152){
         $errors[]='File size must be excately 9 MB';
		 exit();
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"../assets/images/users/".$date.".".$ext[1]);
         //echo "Success";
      }else{
         //print_r($errors);
      }
   }

	    /*insert data to tblLogin*/
		$sqlLogin="INSERT INTO tblLoginClient (userclientloginEmail, userloginPW, userloginPWHash, userloginProfile)
		VALUES ('".$_REQUEST['pw_email']."', '".$_REQUEST['pw_a2b']."', '".md5($_REQUEST['pw_a2b'])."','".$date.".".$ext[1]."');";
		$logid = sqlsrv_query( $conn, $sqlLogin);
	   /*end of data to tblLogin*/	  

		/*insert data to tblUser*/
	    $sqlUser_="INSERT INTO tblClientDetails (detailclientID, detailclientFname, detailclientLname, detailclientIDCompany, detailclientPhoneNumber, detailclientTelNumber, detailclientIDCountry, detailclientIDUserAccountType, detailclientRegisteredDate, detailisLogin, detailclientIsActive, username)
		VALUES (".$_SESSION['PIDclient'].",'".$_REQUEST['fname_a2b']."','".$_REQUEST['lname_a2b']."',".$_REQUEST['company_a2b'].", '".$_REQUEST['pnumber_a2b']."','".$_REQUEST['tel_a2b']."',".$_REQUEST['country_a2b'].",".$_REQUEST['access_a2b'].",GETDATE(),'N','Y','".$_REQUEST['pw_email']."');";
		 $loguser = sqlsrv_query( $conn, $sqlUser_);
		/*end of data to tblUser*/

	
		echo "z!kk]XmzA`]-a28K";
	}

}
else
{ echo "karma";}

?>