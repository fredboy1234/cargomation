<?php
/*anl mine script
version 1.0.1
Author: A2B Dev*/

require_once("connection.php");
require_once('Html2Text.php');
use Html2Text\Html2Text;
/*global var*/
$tbl_name = "vrpt_transhipment";
$tbl_tranship = "dbo.transhipment";


function getShipmentDetails($container){
	
		libxml_use_internal_errors(true);
		$curl_ = curl_init();
		curl_setopt_array(
			$curl_,
			array(
				CURLOPT_URL => 'https://www.anl.com.au/ebusiness/tracking/search?FromHome=true&Reference='.$container.'',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS =>"",
				CURLOPT_HTTPHEADER => array(
				'Content-Type: application/html',
				'Cookie: MustRelease=17.0.7.9c7f05a8; TLCOOKIE=da3605796d451162304628a3e17d5107'
			)
			)
		); 
		curl_setopt($curl_, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		$site_request = curl_exec($curl_);
		curl_close($curl_);
		$Content = htmlentities($site_request);
		
 if(strpos($Content, 'o-datatable c-endtoend--table') !== false) {
	$htmlContent = file_get_contents("https://www.anl.com.au/ebusiness/tracking/search?Reference=$container&FromHome=true");	
	$DOM = new DOMDocument();
	$DOM->loadHTML($htmlContent);
	$Detail = $DOM->getElementsByTagName('td');
    $aDataTableHeaderHTML=[];
	$dt = $aDataTableHeaderHTML;
	
    // #Get header name of the table
	foreach($Detail as $NodeHeader) 
	{
		if(!empty(trim($NodeHeader->textContent))){ /*ignore if empty*/
		$aDataTableHeaderHTML[] = trim($NodeHeader->textContent)."\n";
		}
	}
	/*split data from the site into 5*/
	$data = array_chunk($aDataTableHeaderHTML, 5);
	
	if(count($data)!=0){
		// return print("<pre>".print_r($data,true)."</pre>");
		return $data;
		}
	}
	else{
		return "invalid";
	}
	
}



$context = stream_context_create(
    array(
        'http' => array(
            'follow_location' => false
        )
    )
);

	//Eliminate duplicate in array
	$container_array= array();
	$sql = "Select * from {$tbl_name}";
	$execute = sqlsrv_query($conn, $sql);
	while ($row_data = sqlsrv_fetch_array($execute, SQLSRV_FETCH_ASSOC)) {
		$user_id = $row_data['user_id'];
	if(in_array($row_data['containernumber'],$container_array) == false){
		array_push($container_array,$row_data['containernumber']);
		}
	}
	
    //Process transhipment if doesnt exist
	foreach ($container_array as &$value) {
		$sql_in = "SELECT * from {$tbl_tranship} WHERE container_number = '{$value}'";
		$execute_inside = sqlsrv_query($conn, $sql_in);
		$rows = sqlsrv_has_rows( $execute_inside );
		if ($rows === false){
		  $return = getShipmentDetails($value);
			if($return != "invalid"){
			foreach($return as &$value_trans) {
				if(count($value_trans)>3){
					     $date = date_create($value_trans[0]);
					      $vessel_date = date_format($date,"Y-m-d H:i:s");		
						  $sql_transhipment = "INSERT INTO {$tbl_tranship} (user_id,container_number,date_track,moves,location_city,vessel,voyage)
						  VALUES('{$user_id}','{$value}','{$vessel_date}','{$value_trans[1]}','{$value_trans[2]}','{$value_trans[3]}','{$value_trans[4]}');";
						  $execute = sqlsrv_query($conn, $sql_transhipment);
					}
			    }
			}
	    }
		elseif($rows === true ){
			$sql_in = "SELECT  COUNT (container_number) as container_count from {$tbl_tranship} WHERE container_number = '{$value}'";
			$execute = sqlsrv_query($conn, $sql_in);
			while ($row_data = sqlsrv_fetch_array($execute, SQLSRV_FETCH_ASSOC)) {
				 $count = $row_data['container_count'];
			}
			$return = getShipmentDetails($value);
			echo "DB COUNT: ".$count." MINE COUNT: ".count($return);
			if((int)$count == (int)count($return)){
				echo "DB COUNT: ".$count." MINE COUNT: ".count($return);
			}
			// foreach($return as &$value_trans) {
				// if(count($value_trans)>3){
					     // $date = date_create($value_trans[0]);
					      // $vessel_date = date_format($date,"Y-m-d H:i:s");		
						  // $sql_transhipment = "INSERT INTO {$tbl_tranship} (user_id,container_number,date_track,moves,location_city,vessel,voyage)
						  // VALUES('{$user_id}','{$value}','{$vessel_date}','{$value_trans[1]}','{$value_trans[2]}','{$value_trans[3]}','{$value_trans[4]}');";
						  // $execute = sqlsrv_query($conn, $sql_transhipment);
				// }
			 // }
		 }
	}
	
	
?>
