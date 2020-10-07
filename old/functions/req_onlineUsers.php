<?php
include("../connection.php");

	$sqlOnlineuser="SELECT	
	count(dbo.tblUser.isLogin) as ctrOnline
	FROM
	dbo.tblUser
	WHERE dbo.tblUser.isLogin ='1'";
	$sqlCtrOnline = sqlsrv_query( $conn, $sqlOnlineuser);
	while( $row = sqlsrv_fetch_array( $sqlCtrOnline, SQLSRV_FETCH_ASSOC) ) 
	{
		echo $ctr = $row['ctrOnline'];
	}
?>