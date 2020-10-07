<?php
include("../connection.php");
session_start(); 
			//$sqlLogout ="UPDATE dbo.tblClient
			//SET 
			//dbo.tblClient.isLogin = '0'
			//WHERE
			//dbo.tblClient.clientID=".$_SESSION['PIDClient'].";";
			//$userLogout = sqlsrv_query( $conn, $sqlLogout);
			session_destroy();	
			echo "x";

?>