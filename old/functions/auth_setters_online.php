<?php
include ('../connection.php');
	$sqlOnlineLog =" SELECT
	TOP 1 dbo.tblLogUser.logID,
	dbo.tblLogUser.logIDUser,
	dbo.tblLogUser.logIDDate,
	dbo.tblLogUser.isNew,
	dbo.tblUser.userFname,
	dbo.tblUser.userLname
	FROM
	dbo.tblLogUser
	INNER JOIN dbo.tblUser
	ON dbo.tblLogUser.logIDUser = dbo.tblUser.userID
	WHERE
	dbo.tblLogUser.isNew = 'Y'
	ORDER BY
	dbo.tblLogUser.logID DESC";
	$userOnlineLog = sqlsrv_query( $conn, $sqlOnlineLog);
	while( $row = sqlsrv_fetch_array( $userOnlineLog, SQLSRV_FETCH_ASSOC) ) 
		{
		echo $row['userFname'].",".$row['userLname'];
		}
?>