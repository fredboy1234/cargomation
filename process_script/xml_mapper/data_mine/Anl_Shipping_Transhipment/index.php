<?php
/*anl mine script
version 1.0.1
Author: A2B Dev*/

require_once("connection.php");

/*global var*/
$tbl_name = "vrpt_transhipment";
$tbl_tranship = "dbo.transhipment";
$tbl_transupdate = "dbo.transhipment_updates";


function getShipmentDetails($container){
$url = "https://www.anl.com.au/ebusiness/tracking/previous-search?SearchBy=Container&Reference=";	
		libxml_use_internal_errors(true);
		$curl_ = curl_init();
		curl_setopt_array(
			$curl_,
			array(
				CURLOPT_URL => $url.$container,
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
	$htmlContent = file_get_contents($url.$container);	
	$DOM = new DOMDocument();
	$DOM->loadHTML($htmlContent);
	$Detail = $DOM->getElementsByTagName('td');
    $aDataTableHeaderHTML=[];
	//$dt = $aDataTableHeaderHTML;
	$xpath = new \DOMXpath($DOM);
    $path_vessel = $xpath->query("//tbody/tr/td[@data-label='Vessel']");

	
	$data = [];
	  foreach($path_vessel as $container) {
		if(!empty(trim($container->textContent))){ /*ignore if empty*/ 
		$aDataTableHeaderHTML[] = trim($container->textContent);
		}
	  }
 
  $ctr_vessel = (int)count($aDataTableHeaderHTML);
  if($ctr_vessel > 0){
	  for($x = 1; $x <= $ctr_vessel; $x++){
	   $subpath_vessel = $xpath->query("//tbody/tr[$x]/td");
		foreach($subpath_vessel as $vessel_sub) {
			if(!empty(trim($vessel_sub->textContent))){ /*ignore if empty*/
			 $data[] = trim($vessel_sub->textContent);
			  }
		  }
	  }	
  }
	  $output = array_chunk($data, 5);
	  return $output;
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

if(!isset($_GET['user_id'])){
	die("User not specified");
}
else{
	$user_id = $_GET['user_id'];
}


	//Eliminate duplicate in array
	$container_array= array();
	$sql = "Select * from {$tbl_name} WHERE user_id = '$user_id'";
	$execute = sqlsrv_query($conn, $sql);
	while ($row_data = sqlsrv_fetch_array($execute, SQLSRV_FETCH_ASSOC)) {
		$user_id = $row_data['user_id'];
		$ship_id = $row_data['id'];
	if(in_array($row_data['containernumber'],$container_array) == false){
		array_push($container_array,$row_data['containernumber']);
		}
	}
	
    //Process transhipment if doesnt exist
	
	foreach ($container_array as &$value) {
		$ctr_ref=0;
		$sql_in = "SELECT * from {$tbl_tranship} WHERE container_number = '{$value}'";
		$execute_inside = sqlsrv_query($conn, $sql_in);
		$rows = sqlsrv_has_rows( $execute_inside );
		if ($rows === false){
		  $return = getShipmentDetails($value);
			if($return != "invalid"){
			foreach($return as &$value_trans) {

			$date = date_create($value_trans[0]);
			$vessel_date = date_format($date,"Y-m-d H:i:s");		
			$sql_transhipment = "INSERT INTO {$tbl_tranship} (user_id,container_number,date_track,moves,location_city,vessel,voyage)
								VALUES('{$user_id}','{$value}','{$vessel_date}','{$value_trans[1]}','{$value_trans[2]}','{$value_trans[3]}','{$value_trans[4]}');";
								$execute = sqlsrv_query($conn, $sql_transhipment);
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
			$db_ctr = (int)$count;
			$mine_ctr = (int)count($return);
			//if Data from site == DB data
			if($db_ctr == $mine_ctr){
				foreach($return as &$value_trans) {
					$date = date_create($value_trans[0]);
					$vessel_date = date_format($date,"Y-m-d H:i:s");	
					$sql = "SELECT * from {$tbl_tranship} WHERE container_number = '{$value}' AND date_track = '{$vessel_date}' AND moves='{$value_trans[1]}' AND location_city='{$value_trans[2]}' AND vessel='{$value_trans[3]}' AND voyage='{$value_trans[4]}'";	
					$query = sqlsrv_query($conn, $sql);
					$rows = sqlsrv_has_rows( $query );
					
					$sql_update = "SELECT * from {$tbl_transupdate} WHERE container_number = '{$value}' AND date_track = '{$vessel_date}' AND moves='{$value_trans[1]}' AND location_city='{$value_trans[2]}' AND vessel='{$value_trans[3]}' AND voyage='{$value_trans[4]}'";	
					$query_update = sqlsrv_query($conn, $sql_update);
					$rows_update = sqlsrv_has_rows( $query_update );
					if ($rows === false && $rows_update === false){  
						$sql_transhipment = "INSERT INTO {$tbl_transupdate} (user_id,container_number,date_track,moves,location_city,vessel,voyage)
						VALUES('{$user_id}','{$value}','{$vessel_date}','{$value_trans[1]}','{$value_trans[2]}','{$value_trans[3]}','{$value_trans[4]}');";
						$execute = sqlsrv_query($conn, $sql_transhipment);
					}	
				}
			}
			else
			{
				foreach($return as &$value_trans) {
					$date = date_create($value_trans[0]);
					$vessel_date = date_format($date,"Y-m-d H:i:s");	
					$sql = "SELECT * from {$tbl_tranship} WHERE container_number = '{$value}' AND date_track = '{$vessel_date}' AND moves='{$value_trans[1]}' AND location_city='{$value_trans[2]}' AND vessel='{$value_trans[3]}' AND voyage='{$value_trans[4]}'";	
					$query = sqlsrv_query($conn, $sql);
					$rows = sqlsrv_has_rows( $query );
					if ($rows === false){
						$sql_transhipment = "INSERT INTO {$tbl_tranship} (user_id,container_number,date_track,moves,location_city,vessel,voyage)
						VALUES('{$user_id}','{$value}','{$vessel_date}','{$value_trans[1]}','{$value_trans[2]}','{$value_trans[3]}','{$value_trans[4]}');";
						$execute = sqlsrv_query($conn, $sql_transhipment);
					}	
				}
			}

		 }
	 }
	
	
?>
