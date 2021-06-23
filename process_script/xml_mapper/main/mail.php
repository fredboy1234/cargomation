<?php
/*imap MACRO AUTO SCRIPT
Author: Fred*/

require_once('connection.php');
header("Content-Type: text/html");
ini_set('max_execution_time', 0); //0=NOLIMIT
ini_set('memory_limit', '-1');

$link_table = "dbo.shipment_link";
$var_ship = "shipment_num";
$var_code = "license_code";
$var_col = "(shipment_num,master_bill,house_bill,shipper,client,macro_link,license_code)";

//The location of the mailbox.
$mailbox = base64_decode('e2ltYXAuc2VjdXJlc2VydmVyLm5ldDo5OTUvcG9wMy9zc2wvbm92YWxpZGF0ZS1jZXJ0fQ==');
//The username / email address that we want to login to.
$username = base64_decode('c3VwcG9ydEBjYXJnb21hdGlvbi5jb20=');
//The password for this email address.
$password = base64_decode('VSZOeVJ1dns1WlJoNFsuZA==');


//Attempt to connect using the imap_open function.
try{
$imapResource = imap_open($mailbox, $username, $password);
}
catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
 
//If the imap_open function returns a boolean FALSE value,
//then we failed to connect.
if($imapResource === false){
    //If it failed, throw an exception that contains
    //the last imap error.
    throw new Exception(imap_last_error());
} 

//get data between tags
function get_data($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);   
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}

//If we get to this point, it means that we have successfully
//connected to our mailbox via IMAP.
  
//Lets get all emails that were received since a given date.
$search = 'SINCE "' . date("j F Y", strtotime("-1 day")) . '"';
$emails = imap_search($imapResource, $search);
 
//If the $emails variable is not a boolean FALSE value or
//an empty array.  
if(!empty($emails)){ 
    ##Loop through the emails.
    foreach($emails as $key=>$email){
        ##Fetch an overview of the email.
        $overview = imap_fetch_overview($imapResource, $email);
        $overview = $overview[0]; 
		##filter subject from cargowise Trigger Point
		if(strpos(htmlentities($overview->subject),'Shipment - HTML to Hub') !== false){
			
        ##Print out the subject of the email.
        echo '<b>' . htmlentities($overview->subject) . '</b><br>';
		
        ##Print out the sender's email address / from email address.
        echo 'From: ' . $overview->from . '<br><br>';
		
        ##Get the body of the email using UTF-8 encode.
        $message = imap_fetchbody($imapResource, $email, 1);
        //cho $message = imap_base64($message);
		//echo $message = imap_8bit($message);
       // echo $message = quoted_printable_encode($message);
         
		$job = 		trim(strval(get_data($message, 'ship</span><span>&gt;<s span=""></pre>', '<span>&lt;</span><span>/</span><span>ship')));		
		$shipper =  trim(strval(get_data($message, 'shipper</span><span>&gt;</span>', '<span>&lt;</span><span>/</span><span>shipper')));		
		$client =   trim(strval(get_data($message, 'client</span><span>&gt;</span>', '<span>&lt;</span><span>/</span><span>client')));	
		$mbill = 	trim(strval(get_data($message, 'mbill</span><span>&gt;</span>', '<span>&lt;</span><span>/</span><span>mbill')));		
		$hbill = 	trim(strval(get_data($message, 'hbill</span><span>&gt;</span>', '<span>&lt;</span><span>/</span><span>hbill')));	
	    $mcode = 	trim(strval(get_data($message, 'macro</span><span>&gt;</span>', '<span>&lt;</span><span>/</span><span>macro')));
		$lcode =	trim(strval(get_data($message, 'LicenceCode=', '&amp;ControllerID')));
		
		$sql = "Select * from {$link_table} WHERE {$var_ship} LIKE '%{$job}%' AND {$var_code} = '{$lcode}'";
		$execute_query = sqlsrv_query($conn, $sql);
		$result = sqlsrv_has_rows($execute_query);
		
		if ($result === false) {
		$sql = "Insert into {$link_table} {$var_col} values('{$job}','{$mbill}','{$hbill}','{$shipper}','{$client}','{$mcode}','{$lcode}')";
		$execute_query = sqlsrv_query($conn, $sql);	
			}
		}
    }
} 
imap_close($imapResource);

?>
