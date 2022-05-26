<?php
/*imap MACRO AUTO SCRIPT
Author: Fred*/
if(isset($_GET['email']) && isset($_GET['pass']) && isset($_GET['duration'])){
	$email  = $_GET['email'];
	$pass  = $_GET['pass'];
	$duration  = $_GET['duration'];
}
else{
	die("Invalid Request.");
}

require_once('connection.php');
header("Content-Type: text/html");
date_default_timezone_set("Australia/Sydney");
ini_set('max_execution_time', 0); //0=NOLIMIT
ini_set('memory_limit', '-1');

$link_table = "dbo.shipment_link";
$var_ship = "shipment_num";
$var_code = "license_code";
$var_col = "(shipment_num,master_bill,house_bill,shipper,client,macro_link,license_code)";

//The location of the mailbox.
$mailbox = "{outlook.office365.com:993/imap/ssl/novalidate-cert}";
//The username / email address that we want to login to.
$username = $email;
//The password for this email address.
$password = $pass;


//Attempt to connect using the imap_open function.
try{
$start = microtime(true); // start timer	
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
echo $search = 'SINCE "' . date("j F Y", strtotime("-".$duration." day")) . '"';
$emails = imap_search($imapResource, $search);
//$emails = imap_search($imapResource, "UNSEEN", SE_UID);
 
//If the $emails variable is not a boolean FALSE value or
//an empty array.  
$ctr = 0;
$tempID = "";
$totalMail = 0;
$new_array = array();

if(!empty($emails)){ 
    ##Loop through the emails.
    foreach($emails as $key=>$email){
    	echo $key;
		$overview = imap_fetch_overview($imapResource, $email);
        $overview = $overview[0]; 
			if(strpos(htmlentities($overview->subject),'Shipment - HTML to Hub') !== false){
					$message = imap_fetchbody($imapResource, $email, 1);
					if($username == 'TCF@cargomation.com'){
						$tagLT = "&lt;";
						$tagGT = "&gt;";
						$tagSpan = '<s span="">';
						$tempID = 'TCFSYDSYD';
					}
					elseif($username == 'imageinternational@cargomation.com'){
						$tagLT = "&lt;";
						$tagGT = "&gt;";
						$tagSpan = '<s span="">';
						$tempID = 'IM0SYDSYD';
					}  
						 $message = trim(strval($message));
						 $job = trim(strval(get_data($message, 'ship</span><span>'.$tagGT.''.$tagSpan.'</pre>', '<span>'.$tagLT.'</span><span>/</span><span>ship')));
						 $shipper =  trim(strval(get_data($message, 'shipper</span><span>'.$tagGT.'</span>', '<span>'.$tagLT.'</span><span>/</span><span>shipper')));		
						 $client =   trim(strval(get_data($message, 'client</span><span>'.$tagGT.'</span>', '<span>'.$tagLT.'</span><span>/</span><span>client')));	
						 $mbill = 	trim(strval(get_data($message, 'mbill</span><span>'.$tagGT.'</span>', '<span>'.$tagLT.'</span><span>/</span><span>mbill')));		
						 $hbill = 	trim(strval(get_data($message, 'hbill</span><span>'.$tagGT.'</span>', '<span>'.$tagLT.'</span><span>/</span><span>hbill')));	
						 $mcode = 	trim(strval(get_data($message, 'macro</span><span>'.$tagGT.'</span>', '<span>'.$tagLT.'</span><span>/</span><span>macro')));
						 $code =     trim(strval(str_replace('&amp;', '&', $mcode)));
						 $lcode =	trim(strval(get_data($code, 'LicenceCode=', '&ControllerID')));
						 
					$new_array[] = array("shipment"=>$job, "shipper"=>$shipper,"client"=>$client,"mbill"=>$mbill,"hbill"=>$hbill,"mcode"=>$mcode,"code"=>$code,"lcode"=>$lcode);
		}
		$totalMail++;
    }
     
     	$imapresult=imap_mail_move($imapResource,$totalMail,'INBOX/Saved');
		if($imapresult==false){die(imap_last_error());}
		imap_close($imapResource,CL_EXPUNGE);
    
}
		$sql = "Select * from {$link_table} WHERE {$var_code} = '{$tempID}'";
		$execute_query = sqlsrv_query($conn, $sql);
		while ($row = sqlsrv_fetch_array($execute_query, SQLSRV_FETCH_ASSOC)) {
			$ship_array[]  = $row['shipment_num'];
		}
		$insert_link = "INSERT INTO {$link_table} VALUES ";
		$json_array = json_decode(json_encode ($new_array));
		$ship_insert = "";
		$value_query = "";
		
		if(count($new_array)>= 1){
		for ($i = 0; $i < count($new_array); $i++) {
			 if(!in_array($json_array[$i]->shipment,$ship_array)){
				 if(count($new_array) == 1){
				 $ship_insert .= "('{$json_array[$i]->shipment}','{$json_array[$i]->mbill}','{$json_array[$i]->hbill}','{$json_array[$i]->shipper}','{$client}','{$json_array[$i]->code}','{$json_array[$i]->lcode}')";
				 }
				 else{
				 $ship_insert .= "('{$json_array[$i]->shipment}','{$json_array[$i]->mbill}','{$json_array[$i]->hbill}','{$json_array[$i]->shipper}','{$client}','{$json_array[$i]->code}','{$json_array[$i]->lcode}'),";		
				 }
				 $ctr++;
			 }
		  }
		}
		if(substr($ship_insert, -1) == ","){$value_query = $insert_link.substr($ship_insert, 0, -1);}
		else{$value_query = $insert_link.$ship_insert;}
		
		if(!empty($value_query)){
			sqlsrv_query($conn,$value_query);
		}

echo 'connection: '.(microtime(true)-$start).' seconds'."\r\n"; // show results
echo "Total Processed: ".$ctr." Total Fetched Emails:".$totalMail;
$errors = imap_errors();
//imap_close($imapResource);
?>
