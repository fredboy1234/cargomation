<?php
include ('connection.php');
	$sqlOnlineLog =" UPDATE tblLogUser  
	set isNew='N'
	where logIDUser = (select max(logIDUser) from tblLogUser)";
	$userOnlineLog = sqlsrv_query( $conn, $sqlOnlineLog);
?>