<?php
include ('../connection.php');
$pw="admin123!";

   /*insert data to tblLogin*/
		$sqlLogin="INSERT INTO tblLogin (loginEmail, loginPW, loginPWHash, loginProfile)
		VALUES ('admin@a2bsolutiongroup.com', '$pw', '".md5($pw)."','');";
		$logid = sqlsrv_query( $conn, $sqlLogin);
	/*end of data to tblLogin*/	  
	
	
		/*insert data to tblUser admin*/
		$sqlUser="INSERT INTO tblUser (userFname, userLname, userIDCompany, userPhoneNumber, userTelNumber, userIDCountry, userIDAccessLevel, userRegisteredDate)
		VALUES ('ADMIN', 'A2B',1, '242-000','242-000',15,1,GETDATE());";
		$loguser = sqlsrv_query( $conn, $sqlUser);

?>