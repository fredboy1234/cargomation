<?php
include ('../connection.php');
$email = $_POST['EmailData'];
$sql = "SELECT	*
FROM
dbo.tblLoginClient
WHERE
dbo.tblLoginClient.userclientloginEmail = '$email'";
$checkID = sqlsrv_query( $conn, $sql);
$ifExist = sqlsrv_has_rows ($checkID);
if($ifExist === true){
		echo "1";
	}
	else
	{echo "2";}
?>