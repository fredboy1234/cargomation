<?php
include ('../connection.php');
$email = $_POST['EmailData'];
$sql = "SELECT	tblLogin.loginPW,	tblLogin.loginID,	tblClient.clientID,	tblClient.clientFname,	tblClient.clientLname,	tblLogin.loginEmail
FROM	tblClient	INNER JOIN tblLogin	ON tblClient.clientID = tblLogin.loginID	WHERE	tblLogin.loginEmail = '$email'";
$checkID = sqlsrv_query( $conn, $sql);
$ifExist = sqlsrv_has_rows ($checkID);
if($ifExist === true){
		echo "1";
	}
	else
	{echo "2";}



?>