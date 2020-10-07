<?php
include ('../connection.php');
	$sqlOnlineLog ="UPDATE dbo.tblLogUser  
	set isNew='N'
	where logID = (SELECT TOP 1 logID from tblLogUser ORDER BY logID DESC)";
	$userOnlineLog = sqlsrv_query( $conn, $sqlOnlineLog);
?>