<?php
include ('../connection.php');
session_start();
if (isset($_REQUEST['a2_email']) && isset($_REQUEST['a2_pwhash']))
{
    if (empty(trim($_REQUEST['a2_email'])) == false && empty(trim($_REQUEST['a2_pwhash'])) == false)
    {
        $email = $_REQUEST['a2_email'];
        $pw = md5($_REQUEST['a2_pwhash']);
        $sql = "SELECT	tblLogin.loginPW, tblLogin.loginPWHash,	tblLogin.loginID, tblLogin.loginProfile,	tblUser.userID,	tblUser.userFname,	tblUser.userLname,	tblLogin.loginEmail, dbo.tblAccessLevel.userAccessLevel
		FROM	tblUser	INNER JOIN tblLogin	ON tblUser.userID = tblLogin.loginID
		INNER JOIN dbo.tblAccessLevel
		ON tblUser.userIDAccessLevel = dbo.tblAccessLevel.userIDAccessLevel
		WHERE	tblLogin.loginEmail = '$email'	AND tblLogin.loginPWHash = '$pw'";
        $checkID = sqlsrv_query($conn, $sql);
        $ifExist = sqlsrv_has_rows($checkID);

        if ($ifExist === true)
        {
            while ($row = sqlsrv_fetch_array($checkID, SQLSRV_FETCH_ASSOC))

            {
                $_SESSION['PID'] = $row['loginID'];
                $_SESSION['PEMAIL'] = $row['loginEmail'];
                $_SESSION['FNAME'] = $row['userFname'] . "," . $row['userLname'];
                $_SESSION['ACCESSLEVEL'] = $row['userAccessLevel'];
                $accessLevel = $row['userAccessLevel'];
                $_SESSION['img'] = $row['loginProfile'];
            }

            $sqlOnline = "UPDATE dbo.tblUser
			SET 
			dbo.tblUser.isLogin = '1'
			WHERE
			dbo.tblUser.userID=" . $_SESSION['PID'] . ";";
            $userOnline = sqlsrv_query($conn, $sqlOnline);

            $sqlOnlineLog = "INSERT INTO tblLogUser (logIDUser, logIDDate, isNew)
			VALUES ('" . $_SESSION['PID'] . "',GETDATE(),'Y');";
            $userOnlineLog = sqlsrv_query($conn, $sqlOnlineLog);

            echo "1";
            exit();
        }
        elseif ($ifExist === false)
        {
        $sqlClient = "SELECT	tblLogin.loginPW, tblLogin.loginPWHash,	tblLogin.loginID, tblLogin.loginProfile,  tblClient.clientID,	tblClient.clientFname,	tblClient.clientLname, tblClient.clientIsActive,	tblLogin.loginEmail, dbo.tblAccessLevel.userAccessLevel
		FROM	tblClient INNER JOIN tblLogin	ON tblClient.clientID = tblLogin.loginID
		INNER JOIN dbo.tblAccessLevel
		ON tblClient.clientIDAccessLevel = dbo.tblAccessLevel.userIDAccessLevel
		WHERE	tblLogin.loginEmail = '$email'	AND tblLogin.loginPWHash = '$pw'";
            $checkIDClient = sqlsrv_query($conn, $sqlClient);
			
            $ifExistClient = sqlsrv_has_rows($checkIDClient);
			
            while ($row = sqlsrv_fetch_array($checkIDClient, SQLSRV_FETCH_ASSOC))
            {
                $isActivated = $row['clientIsActive'];
            }
            if ($ifExistClient === true)
            {

                if ($isActivated === 'Y')
                {
                    $sqlClient = "SELECT	tblLogin.loginPW, tblLogin.loginPWHash,	tblLogin.loginID, tblLogin.loginProfile,  tblClient.clientID, tblClient.clientPhoneNumber, tblClient.clientTelNumber,	tblClient.clientFname, tblClient.clientSubscriptionID,	tblClient.clientLname, tblClient.clientIsActive,	tblLogin.loginEmail, dbo.tblAccessLevel.userAccessLevel
					FROM	tblClient INNER JOIN tblLogin	ON tblClient.clientID = tblLogin.loginID
					INNER JOIN dbo.tblAccessLevel
					ON tblClient.clientIDAccessLevel = dbo.tblAccessLevel.userIDAccessLevel
					WHERE	tblLogin.loginEmail = '$email'	AND tblLogin.loginPWHash = '$pw'";
                    $checkIDClient = sqlsrv_query($conn, $sqlClient);
                    while ($row1 = sqlsrv_fetch_array($checkIDClient, SQLSRV_FETCH_ASSOC))
                    {
                        $_SESSION['PIDclient'] = $row1['loginID'];
                        $id_hold = $row1['loginID'];
                        $_SESSION['PEMAIL'] = $row1['loginEmail'];
                        $subID = $row1['clientSubscriptionID'];
                        $_SESSION['SUBID'] = $row1['clientSubscriptionID'];
                        $_SESSION['NUMBERDETAILS'] = $row1['clientPhoneNumber'] . " | " . $row1['clientTelNumber'];
                        $_SESSION['FNAME'] = $row1['clientFname'] . "," . $row1['clientLname'];
                        $_SESSION['ACCESSLEVEL'] = $row1['userAccessLevel'];
                        $accessLevel = $row1['userAccessLevel'];
                        $_SESSION['img'] = $row1['loginProfile'];
                    }
                    $getSubscription = "SELECT
					dbo.tblSubscription.subscriptionID,
					dbo.tblSubscription.subscriptionName,
					dbo.tblSubscription.subscriptionUserCapacity
					FROM
					dbo.tblSubscription
					WHERE
					dbo.tblSubscription.subscriptionID =$subID";
					$checkIDSubscription = sqlsrv_query($conn, $getSubscription);
					while ($rowSub = sqlsrv_fetch_array($checkIDSubscription, SQLSRV_FETCH_ASSOC))
					{
						$_SESSION['SUBSCRIPTION'] = $rowSub['subscriptionName'];
						$_SESSION['SUBSCRIPTIONCAPACITY'] = $rowSub['subscriptionUserCapacity'];
					}
			
					$sqlCountUser = "SELECT
					count(dbo.tblClientDetails.detailclientID) as ctrRemain
					FROM
					dbo.tblClientDetails
					WHERE
					dbo.tblClientDetails.detailclientID = " . $id_hold . "
					GROUP BY
					dbo.tblClientDetails.detailclientID";
                    $checkCountRemain = sqlsrv_query($conn, $sqlCountUser);

					$ifCounterExist = sqlsrv_has_rows($checkCountRemain);
					
					if($ifCounterExist === true){
					
                    while ($row_count = sqlsrv_fetch_array($checkCountRemain, SQLSRV_FETCH_ASSOC))
                    {
                        $_SESSION['CREATEDUSER'] = $row_count['ctrRemain'];
                        $_SESSION['REMAININGUSER'] = (int)$_SESSION['SUBSCRIPTIONCAPACITY'] - (int)$row_count['ctrRemain'];

                    }}
					else{
					$_SESSION['CREATEDUSER'] = 0;
                    $_SESSION['REMAININGUSER'] = $_SESSION['SUBSCRIPTIONCAPACITY'];
					}

                    echo "3";
                    exit();
                }
                else
                {
                    echo "4";
                    exit();
                }
            }
            else
            {
                echo "2";
            }

        }
        else
        {
            echo "2";
            exit();
        }
    }
    else
    {
        echo "2";
        exit();
    }
}
?>
