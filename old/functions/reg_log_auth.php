<?php
include ('../connection.php');
session_start(); 

function generateRandomString($length = 25) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
if(isset($_REQUEST['pw_email']) && isset($_REQUEST['pw_a2b']) && isset($_SESSION['PID'])){
	
	
$sql = "SELECT	tblLogin.loginPW,	tblLogin.loginID,	tblClient.clientID,	tblClient.clientFname,	tblClient.clientLname,	tblLogin.loginEmail
FROM	tblClient	INNER JOIN tblLogin	ON tblClient.clientID = tblLogin.loginID	WHERE	tblLogin.loginEmail = '".$_REQUEST['pw_email']."'";


$checkID = sqlsrv_query( $conn, $sql);
$ifExist = sqlsrv_has_rows ($checkID);
if($ifExist === true)
	{
	echo "1";
	exit();
	}
	else
	{
		
		
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
         $errors[]='File size must be excately 2 MB';
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
		$sqlLogin="INSERT INTO tblLogin (loginEmail, loginPW, loginPWHash, loginProfile)
		VALUES ('".$_REQUEST['pw_email']."', '".$_REQUEST['pw_a2b']."', '".md5($_REQUEST['pw_a2b'])."','".$date.".".$ext[1]."');";
		
		$logid = sqlsrv_query( $conn, $sqlLogin);
	/*end of data to tblLogin*/	  
	
	
	if(strval($_REQUEST['access_a2b']) == '1'){
		/*insert data to tblUser admin*/
		$sqlUser="INSERT INTO tblUser (userFname, userLname, userIDCompany, userPhoneNumber, userTelNumber, userIDCountry, userIDAccessLevel, userRegisteredDate)
		VALUES ('".$_REQUEST['fname_a2b']."', '".$_REQUEST['lname_a2b']."',".$_REQUEST['company_a2b'].", '".$_REQUEST['pnumber_a2b']."','".$_REQUEST['tel_a2b']."',".$_REQUEST['country_a2b'].",".$_REQUEST['access_a2b'].",GETDATE());";
		$loguser = sqlsrv_query( $conn, $sqlUser);
		/*end of data to tblUser*/	
		echo "z!kk]XmzA`]-a28K";		
	}
	elseif(strval($_REQUEST['access_a2b']) == '2'){
		$xpath = $_REQUEST['pw_email'];
		$sqlsubscription = "SELECT
		dbo.tblSubscription.subscriptionID,
		dbo.tblSubscription.subscriptionName,
		dbo.tblSubscription.subscriptionUserCapacity
		FROM
		dbo.tblSubscription
		WHERE  dbo.tblSubscription.subscriptionID =".$_REQUEST['subscribe_a2b']."";	
		$sqlSub = sqlsrv_query( $conn, $sqlsubscription);
		while( $row = sqlsrv_fetch_array( $sqlSub, SQLSRV_FETCH_ASSOC) ) 
				{
				 $NoOfUsers = $row['subscriptionUserCapacity'];
				}
		/*insert data to tblUser*/
	    $sqlUser="INSERT INTO tblClient (clientFname, clientLname, clientIDCompany, clientPhoneNumber, clientTelNumber, clientIDCountry, clientIDAccessLevel, clientRegisteredDate, isLogin, clientSubscriptionID, clientSubscriptionNoOfUsers, clientIsActive)
		VALUES ('".$_REQUEST['fname_a2b']."','".$_REQUEST['lname_a2b']."',".$_REQUEST['company_a2b'].", '".$_REQUEST['pnumber_a2b']."','".$_REQUEST['tel_a2b']."',".$_REQUEST['country_a2b'].",".$_REQUEST['access_a2b'].",GETDATE(),'N',".$_REQUEST['subscribe_a2b'].",".$NoOfUsers.",'Y');";
		 $loguser = sqlsrv_query( $conn, $sqlUser);
		/*end of data to tblUser*/
	$rand = generateRandomString().generateRandomString();	
	}
		echo "z!kk]XmzA`]-a28K";
	}

}

?>