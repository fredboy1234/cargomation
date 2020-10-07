<?php
$email = $_GET['xpathemail']
$sqlUpdate ="UPDATE tblLogin INNER JOIN tblClient ON tblLogin.loginID = tblClient.clientID SET tblClient.clientIsActive = 'Y'
WHERE (((tblLogin.loginEmail)='$email') AND ((tblClient.clientIsActive)='N'));"


?>