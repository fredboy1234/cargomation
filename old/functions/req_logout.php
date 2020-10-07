<?php
include("../connection.php");
session_start(); 
			$sqlLogout ="UPDATE dbo.tblUser
			SET 
			dbo.tblUser.isLogin = '0'
			WHERE
			dbo.tblUser.userID=".$_SESSION['PID'].";";
			$userLogout = sqlsrv_query( $conn, $sqlLogout);
			session_destroy();	
			echo "x";

?>