<?php
include ('../connection.php');
session_start();
if (isset($_REQUEST['a2_email_']) && isset($_REQUEST['a2_pwhash_']))
{
    if (empty(trim($_REQUEST['a2_email_'])) == false && empty(trim($_REQUEST['a2_pwhash_'])) == false)
    {
        $email = $_REQUEST['a2_email_'];
        $pw = md5($_REQUEST['a2_pwhash_']);
        $sql = "SELECT	*	FROM
		dbo.tblClientDetails
		INNER JOIN dbo.tblLoginClient
		ON dbo.tblClientDetails.username = dbo.tblLoginClient.userclientloginEmail
		INNER JOIN dbo.tblClientAccessLevel
		ON dbo.tblClientDetails.detailclientIDUserAccountType = dbo.tblClientAccessLevel.clientIDAccessLevel
		WHERE
		dbo.tblLoginClient.userclientloginEmail = '$email'
		AND dbo.tblLoginClient.userloginPWHash = '$pw'";
        $checkID = sqlsrv_query($conn, $sql);
        $ifExist = sqlsrv_has_rows($checkID);

        if ($ifExist === true)
        {
            while ($row = sqlsrv_fetch_array($checkID, SQLSRV_FETCH_ASSOC))

            {
                $_SESSION['detailclientID'] = $row['detailclientID'];
                $_SESSION['userclientloginEmail'] = $row['userclientloginEmail'];
                $_SESSION['userclientname'] = $row['detailclientLname'] . "," . $row['detailclientFname'];
                $_SESSION['ACCESSLEVEL'] = $row['clientAccessLevel'];
                $_SESSION['userloginProfile'] = $row['userloginProfile'];
            }

        //$sqlOnline = "UPDATE dbo.tblUser
		//SET 
		//dbo.tblUser.isLogin = '1'
		//WHERE
		//dbo.tblUser.userID=" . $_SESSION['PID'] . ";";
        //$userOnline = sqlsrv_query($conn, $sqlOnline);
		//
        //$sqlOnlineLog = "INSERT INTO tblLogUser (logIDUser, logIDDate, isNew)
		//VALUES ('" . $_SESSION['PID'] . "',GETDATE(),'Y');";
        //$userOnlineLog = sqlsrv_query($conn, $sqlOnlineLog);
		//
        echo "1";
        exit();
        }

    else
    {
        echo "2";
        exit();
    }
}}
?>
