<?php
include("../connection.php");
session_start(); 
	$sqlOnlineuser="SELECT
	dbo.tblUser.userFname,
	dbo.tblUser.userLname,
	dbo.tblAccessLevel.userAccessLevel
	FROM
	dbo.tblAccessLevel
	INNER JOIN dbo.tblUser
	ON dbo.tblAccessLevel.userIDAccessLevel = dbo.tblUser.userIDAccessLevel
	WHERE
	dbo.tblUser.isLogin = '1'";
	$sqlCtrOnline = sqlsrv_query( $conn, $sqlOnlineuser);
	while( $row = sqlsrv_fetch_array( $sqlCtrOnline, SQLSRV_FETCH_ASSOC) ) 
	{
		echo '<a href="javascript:void(0)"><img src="assets/images/users/'.$_SESSION['img'].'.png" alt="user-img" class="img-circle"> <span>'.$row['userFname']." ".$row['userLname'].'<small class="text-success">online</small></span></a>';
	}
?>