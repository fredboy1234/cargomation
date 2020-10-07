<?php
include ('../connection.php');
session_start(); 
if(isset($_REQUEST['cName']) && isset($_REQUEST['cAddress']) && isset($_SESSION['PID']))
{
	/*insert data to tblUser*/
		$sqlCompany="INSERT INTO tblCompany (companyName, companyAddress, Address_1, City, infoState, postalCode, idCountryCode)
		VALUES ('".$_REQUEST['cName']."', '".$_REQUEST['cAddress']."','".$_REQUEST['cAddress1']."', '".$_REQUEST['cCity']."','".$_REQUEST['cState']."','".$_REQUEST['cPostal']."','".$_REQUEST['cCountry']."');";	
		$logcompany = sqlsrv_query( $conn, $sqlCompany);
	/*end of data to tblUser*/	  
		echo "z!kk`]-a28K]XmzA";
}

?>