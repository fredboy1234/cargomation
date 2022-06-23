<?php
//*** custom function ***//
require_once ('json.php');
require_once ('jsonpath-0.8.1.php');
require_once ('connection.php');
error_reporting(E_ALL ^ E_WARNING); 
set_time_limit(0);
ini_set('memory_limit', '-1');
$GLOBALS['parser'] = new __Services_JSON(SERVICES_JSON_LOOSE_TYPE);
$GLOBALS['curl'] = curl_init();
$xmlType = $ata = $atd = $eta = $etd = $shipNumber = $consolNumber = $order_number = $sending_agent = $sending_add = $receiving_agent = $receiving_add = $state = $containercollection = $res = '';


/*xml xpath declaration*/
$path_DataSource ="$.Body.UniversalShipment.Shipment.DataContext.DataSourceCollection.DataSource";
$path_UniversalShipment = "$.Body.UniversalShipment.Shipment";
$path_UniversalSubShipment = "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment";
$path_TransportLegCollection = "$.Body.UniversalShipment.Shipment.TransportLegCollection.TransportLeg";
$path_TransportLegDataSet = "$.Body.UniversalShipment.Shipment.TransportLegCollection";
$path_PackingLineCollection = "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.PackingLineCollection";
$path_RelatedPackingLineCollection = "$.Body.UniversalShipment.Shipment.PackingLineCollection";
$path_OrganizationAddressCollection = "$.Body.UniversalShipment.Shipment.OrganizationAddressCollection.OrganizationAddress";
$path_SubOrganizationAddressCollection = "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress";
$path_RelatedShipmentOrgCollection = "$.Body.UniversalShipment.Shipment.RelatedShipmentCollection.RelatedShipment.OrganizationAddressCollection.OrganizationAddress";
$path_OrderNumberCollection = "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.LocalProcessing.OrderNumberCollection";
$path_ContainerCollection = "$.Body.UniversalShipment.Shipment.ContainerCollection.Container";
$path_ContainerCollectionMultiple = "$.Body.UniversalShipment.Shipment.ContainerCollection";
$path_MilestoneCollection = "$.Body.UniversalShipment.Shipment.MilestoneCollection";
$path_MilestoneCollectionSub = "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.MilestoneCollection";
$path_RelatedMilestoneCollection = "$.Body.UniversalShipment.Shipment.RelatedShipmentCollection.RelatedShipment.MilestoneCollection";
$GLOBALS['path_GetDocument'] = "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument";
/*end of xml xpath declaration*/


function addShipment($value_array,$shipNumber){
	$var = "";
	$col_field = "(user_id,console_id,shipment_num,master_bill,house_bill,transport_mode,vessel_name,voyage_flight_num,vesslloyds,eta,etd,place_delivery,place_receipt,consignee,consignor,sending_agent,receiving_agent,receiving_agent_addr,sending_agent_addr,consignee_addr,consignor_addr,trigger_date,container_mode,port_loading,port_discharge,order_number,totalvolume,ata,atd,route_leg,organization,packingline,container,milestone)";
	foreach ($value_array as $key => $value) {
		if(!empty($value)){ $var .= "'".$value."',"; }
		elseif($key == 26){	$var .= "".$value.",";	 }
	    else{ $value = ""; 	$var .= "'".$value."',"; }
	}

	$checkShipment = "SELECT DISTINCT TOP 1 dbo.shipment.shipment_num FROM dbo.shipment WHERE dbo.shipment.shipment_num = '".$shipNumber."' AND dbo.shipment.user_id ='".$GLOBALS['user_id']."'";
	$sql =  execQuery($checkShipment);
	$row_count = sqlsrv_num_rows($sql);
	if($row_count > 0){
		updateShipment($value_array,$shipNumber);
	}else{
		$var = substr($var, 0, -1);		
	 	$sql = "INSERT INTO shipment {$col_field} VALUES ({$var})";
	 	$res = execQuery($sql);
	 	
		###Check if successfully inserted
		 if(!$res){
		 	return 'failed';
		 }else{
		###Get Document after success insert
			$GLOBALS['ship_id'] = getLastShipID($shipNumber,$GLOBALS['user_id']);
			if($GLOBALS['ship_id'] != false){
				getDocumentRequest($shipNumber,"Add", $GLOBALS['ship_id'] , $GLOBALS['ship_id']);
			}	
		###End of Get Document after success insert	
			return 'success';
		 }
		###End of Check if successfully inserted
	}
}

function updateShipment($value_array,$shipNumber){
	$pass_array = array();
	$b = json_decode(json_encode($value_array));
    foreach ($b as $key => $value) {
    	if(!empty($value) || $value == "" ){
    		array_push($pass_array,$value);
    	}else{
    		array_push($pass_array,null);
    	}
    }
    
	if(count($pass_array)>= 1){
		for ($i = 0; $i < count($pass_array); $i++) {
			if(empty($pass_array[26])){
				$vol = 0;
			}else{
				$vol = $pass_array[26];
			}
			$sql = "Set console_id='$pass_array[1]', master_bill ='$pass_array[3]', house_bill='$pass_array[4]', transport_mode='$pass_array[5]',
			vessel_name='$pass_array[6]', voyage_flight_num='$pass_array[7]', vesslloyds='$pass_array[8]', eta='$pass_array[9]',
			etd='$pass_array[10]', place_delivery='$pass_array[11]', place_receipt='$pass_array[12]',
			consignee='$pass_array[13]',consignor='$pass_array[14]',sending_agent='$pass_array[15]',receiving_agent='$pass_array[16]',
			receiving_agent_addr='$pass_array[17]',sending_agent_addr='$pass_array[18]',consignee_addr='$pass_array[19]',
			consignor_addr='$pass_array[20]',trigger_date='$pass_array[21]', container_mode='$pass_array[22]', port_loading='$pass_array[23]', 
			port_discharge='$pass_array[24]', order_number='$pass_array[25]', totalvolume=$vol, ata='$pass_array[27]', atd='$pass_array[28]',
			route_leg='$pass_array[29]', organization='$pass_array[30]',packingline='$pass_array[31]',container='$pass_array[32]',milestone='$pass_array[33]'";
		}
	} 
	 $sql = "UPDATE Shipment {$sql} WHERE shipment_num = '".$shipNumber."' AND user_id = ".$GLOBALS['user_id']."";
	 $res = execQuery($sql);
	 
	###Check if successfully updated
	 if(!$res){
		$GLOBALS['err_update'] = $sql;
	 	return 'failed';
	 }else{
	###Get Document after update
		$GLOBALS['ship_id'] = getLastShipID($shipNumber,$GLOBALS['user_id']);
		if($GLOBALS['ship_id'] != false){
			getDocumentRequest($shipNumber,"Update", $GLOBALS['ship_id']);
		}	
	###End of Get Document after success insert	
		return 'success';
	 }
	###End of Check if successfully inserted
}


function getDocumentRequest($value,$action,$ship_id){
	$shipNumber	= $value;
	if(strpos($shipNumber, 'B') !== false){
		$requestType = "CustomsDeclaration";
	}else{
		$requestType = "ForwardingShipment";
	}
	$enterprise_id = $GLOBALS['enterprise_id'];
	$company_code = $GLOBALS['company_code'];
	$server_id = $GLOBALS['server_id'];
	$auth = $GLOBALS['auth'];
	$curl = curl_init();
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => $GLOBALS['webservicelink'],CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",	CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",CURLOPT_POSTFIELDS => 
				"<UniversalDocumentRequest xmlns=\"http://www.cargowise.com/Schemas/Universal/2011/11\" version=\"1.1\">\r\n
				<DocumentRequest>\r\n<DataContext>\r\n
				<DataTargetCollection>\r\n<DataTarget>\r\n
				<Type>$requestType</Type>\r\n<Key>$shipNumber</Key>\r\n
				</DataTarget>\r\n</DataTargetCollection>\r\n
				<Company>\r\n<Code>$company_code</Code>\r\n
				</Company>\r\n<EnterpriseID>$enterprise_id</EnterpriseID>\r\n
				<ServerID>$server_id</ServerID>\r\n</DataContext>\r\n
				</DocumentRequest>\r\n</UniversalDocumentRequest>",
				CURLOPT_HTTPHEADER => array(
				"Authorization: Basic $auth",
				"Content-Type: application/xml",
				"Cookie: WEBSVC=f50e2886473c750f"
				)
			)
		);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$xml = curl_exec($curl);
		curl_close($curl);
	   ###End of Curl Function get UniversalDocumentRequest using SOAP

		$xml = simplexml_load_string($xml);
	    $documentXML = json_encode($xml, JSON_PRETTY_PRINT);
		$DocumentCollection =  str_replace(array("[[", "]]"), array("[", "]"),parseJson(json_decode($documentXML, true), $GLOBALS['path_GetDocument'],""));
		$documentcount = json_decode($DocumentCollection,true);
		$add_document = array();
		$push_document = array();
		$doc_count = (int)count($documentcount);

	###Execute add document if action type is add / new job		
	if($action === "Add"){
		if($documentcount != false){
			if($doc_count> 0){
					foreach ($documentcount as $key => $value) {
					  $fileName = str_replace(["\\","/","#","+",":","*","?",'"','<','>','|',"'"],'',$value['FileName']);
					  $add_document = ["FileName"=>$fileName,"FileType"=>$value['Type']['Code'],"SavedBy"=>$value['SavedBy']['Name'],"SavedDate"=>$value['SaveDateUTC'],"EventDate"=>date("Y-m-d H:i:s"),"Source"=>"cargowise","IsPublished"=>$value['IsPublished'],"ImageData"=>$value['ImageData']];
				      		array_push($push_document,$add_document);
				   	}
							addDocument($push_document,$shipNumber,$ship_id);				
				}
			}else{
					logFile("document_log.txt","No Document found in Job# :".$value." XML Response: <br />");
			}
		}
	###End of Execute add document if action type is add / new job

	###Execute Update document/delete document if type is update (existing job)
	else{
		if($documentcount != false){
			if($doc_count> 0){
					foreach ($documentcount as $key => $value) {
					  $fileName = str_replace(["\\","/","#","+",":","*","?",'"','<','>','|',"'"],'',$value['FileName']);
					  $add_document = ["FileName"=>$fileName,"FileType"=>$value['Type']['Code'],"SavedBy"=>$value['SavedBy']['Name'],"SavedDate"=>$value['SaveDateUTC'],"EventDate"=>date("Y-m-d H:i:s"),"Source"=>"cargowise","IsPublished"=>$value['IsPublished'],"ImageData"=>$value['ImageData']];
				      		array_push($push_document,$add_document);
				   	}
							updateDocument($push_document,$shipNumber,$ship_id);
							unset($push_document);				
				}
			}else{
				$cgmDocArr = getCgmDoc($shipNumber,$ship_id);
				###Delete if cargomation has file but not in cargowise
				if(count($cgmDocArr)>0){
					execQuery("DELETE FROM dbo.document WHERE dbo.document.shipment_id = '".$ship_id."' AND dbo.document.shipment_num ='".$shipNumber."'");
				}
			}
		}
	###End of Execute Update document/delete document if type is update (existing job)	
}

function addDocument($value_array,$shipNumber,$ship_id){

	$var = "";
	$col_field = "(shipment_id ,shipment_num, type, name, saved_by, saved_date, event_date, path, upload_src,is_published)";
	$filepath = 'E:/A2BFREIGHT_MANAGER/'.$GLOBALS['client_email'].'/CW_FILE/';
	foreach ($value_array as $key => $value) {
		if(count($value_array) === 1){
			$var  = "('".$ship_id."','".$shipNumber."','".$value['FileType']."','".$value['FileName']."','".$value['SavedBy']."','".$value['SavedDate']."','".$value['EventDate']."','".$filepath.$shipNumber.'/'.$value['FileType'].'/'.$value['FileName']."','".$value['Source']."','".$value['IsPublished']."')";
		}else{
			if($key === array_key_first($value_array)){
				$var .= "('".$ship_id."','".$shipNumber."','".$value['FileType']."','".$value['FileName']."','".$value['SavedBy']."','".$value['SavedDate']."','".$value['EventDate']."','".$filepath.$shipNumber.'/'.$value['FileType'].'/'.$value['FileName']."','".$value['Source']."','".$value['IsPublished']."'),";
			}elseif($key === array_key_last($value_array)){
				$var .= "('".$ship_id."','".$shipNumber."','".$value['FileType']."','".$value['FileName']."','".$value['SavedBy']."','".$value['SavedDate']."','".$value['EventDate']."','".$filepath.$shipNumber.'/'.$value['FileType'].'/'.$value['FileName']."','".$value['Source']."','".$value['IsPublished']."')";
			}else{
				$var .= "('".$ship_id."','".$shipNumber."','".$value['FileType']."','".$value['FileName']."','".$value['SavedBy']."','".$value['SavedDate']."','".$value['EventDate']."','".$filepath.$shipNumber.'/'.$value['FileType'].'/'.$value['FileName']."','".$value['Source']."','".$value['IsPublished']."'),";
			}	
		}
	}
	
	$sql = "INSERT INTO document {$col_field} VALUES $var";
	$res = execQuery($sql);
	if($res){
	 	base64_Decoder($value_array,$filepath,$shipNumber);
	 	unset($value_array);
	 	logFile("document_log.txt","Successfully added file(s) ".$var." to Job #".$shipNumber.'');
	}
}

function updateDocument($value_array,$shipNumber,$ship_id){
$cgmDocArr = getCgmDoc($shipNumber,$ship_id);
$filepath = 'E:/A2BFREIGHT_MANAGER/'.$GLOBALS['client_email'].'/CW_FILE/';
$cw_document = array();
$new_document = array();

###Check if new file is not exist, then push and compare
foreach ($value_array as $key => $value) {
if(ifDocumentExist($value['FileName'],$value['FileType'],$shipNumber,$ship_id) === false){
	$add_document = ["FileName"=>$value['FileName'],"FileType"=>$value['FileType'],"SavedBy"=>$value['SavedBy'],"SavedDate"=>$value['SavedDate'],"EventDate"=>date("Y-m-d H:i:s"),"Source"=>"cargowise","IsPublished"=>$value['IsPublished'],"ImageData"=>$value['ImageData']];
		array_push($new_document,$add_document);
	}else{
		$existing_document = ["FileName"=>$value['FileName'],"FileType"=>$value['FileType'],"shipment_num"=>$shipNumber,"ship_id"=>$ship_id];
		array_push($cw_document,$existing_document);
	}
}

###Add file if new
if(count($new_document) > 0){
	addDocument($new_document,$shipNumber,$ship_id);
	base64_Decoder($new_document,$filepath,$shipNumber);
	unset($new_document);
	logFile("document_log.txt","Successfully added new file to Job #".$shipNumber.'');
}

###Remove file if not exist in cargowise
$remove_shipmentfile = array();
$new_array = array();
foreach ($cgmDocArr as $key_1 => $value) {
	if(array_search($value['file_name'], array_column($cw_document, 'FileName')) === false)	{
		$new_array[] = array("file_name"=>$value['file_name'], "type"=>$value['type'],"ship_id"=>$value['ship_id'],"shipment_num"=>$value['shipment_num'],"path"=>$value['path']);
		removeShipmentFile($new_array);
		unset($new_array);
	  	logFile("document_log.txt","Successfully removed file ".$value['file_name']." to Job #".$shipNumber.'');
		}		
	}
	unset($cw_document);
###End of Remove file if not exist in cargowise
}

/*replace special characters*/
function getArrayName($val){
	return str_replace(array('["','"]','\"','"','[',']','\/'),array("","","","","","","/"),$val);
}

/*Execute sql query*/
function execQuery($sql){
	return $result = sqlsrv_query($GLOBALS['conn'], $sql, array(), array( "Scrollable" => 'static'));
}

function removeSingleQuote($val){
 return str_replace(array("'", "'"), array("", ""),$val);
}

/*File XML to Json*/
function XMLtoJSON($filename){
	$xml_content = file_get_contents($filename);
	$xml = simplexml_load_string($xml_content);
	$universalshipment = json_encode($xml, JSON_PRETTY_PRINT);
	$result = json_decode($universalshipment, true);

	return $result;
}

/*File Json Parser*/
function parseJson($xpath ,$xpath_source, $key_value){
	$parser = new __Services_JSON(SERVICES_JSON_LOOSE_TYPE);
	$value = jsonPath($xpath, $xpath_source.$key_value);
	return $parsed_value = $parser->encode($value);
}

function node_exist($value){
	if($value == 'false' || $value == ""){
		return "";
	}
	else{
		return $value;
	}	
}

/*decodes base_64 to file path*/
function base64_Decoder($value_array,$filepath,$shipNumber){
	if(count($value_array) > 0){
		foreach ($value_array as $key => $value) {
			$path = $filepath.$shipNumber.'/'.$value['FileType'].'/';
			$base_64 = str_replace(array('["', '"]', '\/'), array("", "", "/"), $value['ImageData']);
			if (!is_dir($path)) {
				mkdir($path, 0777, true);
				file_put_contents($path.$value['FileName'], base64_decode($base_64));
			}else{
				file_put_contents($path.$value['FileName'], base64_decode($base_64));
			}
		}
	}
}

function moveFile($value,$file){
	if($value === 'success'){
		$success = "E:/A2BFREIGHT_MANAGER/".$GLOBALS['client_email']."/CW_SUCCESS/";						
		if(!file_exists($success.$file)){
		rename($file, $success . pathinfo($file, PATHINFO_BASENAME));
		}
	}else{
		$failed = "E:/A2BFREIGHT_MANAGER/".$GLOBALS['client_email']."/CW_ERROR/";						
		if(!file_exists($failed.$file)){
		rename($file, $failed . pathinfo($file, PATHINFO_BASENAME));
		}
	}
}

/*get client email*/
function getClientEmail($user_id){
	$sql =  execQuery("SELECT DISTINCT TOP (1) * FROM [dbo].[user_info] WHERE [user_id] = '".$user_id."'");
	$row_count = sqlsrv_num_rows($sql);
	if($row_count > 0){
		while ($row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
			$client_email = $row['email'];
			$GLOBALS['client_email'] = $client_email;
			$GLOBALS['user_id'] 	 = $user_id; 
		}
		return $client_email;
	}else{
		return false;
	}
}

function getWebService($user_id){
	$sql = execQuery("SELECT DISTINCT TOP (1) * FROM [dbo].[user_webservice] WHERE [user_id] = '".$user_id."' AND isactive='Y'");
	$row_count = sqlsrv_num_rows($sql);
	if($row_count > 0){
		while ($row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
			$GLOBALS['webservicelink'] = $row['webservice_link'];
			$GLOBALS['service_user']   = $row['webservice_username'];
			$GLOBALS['service_password'] = $row['webservice_password'];
			$GLOBALS['server_id'] 	   = $row['server_id'];
			$GLOBALS['enterprise_id']  = $row['enterprise_id'];
			$GLOBALS['company_code']   = $row['company_code'];
			$GLOBALS['auth'] = base64_encode($GLOBALS['service_user'] . ":" . $GLOBALS['service_password']);
		}
	}else{
			return "Web Service Failed";
	}
}

function ifShipmentExist($key){
	$value = "SELECT DISTINCT TOP 1 dbo.shipment.shipment_num FROM dbo.shipment WHERE dbo.shipment.shipment_num = '".$key."' AND dbo.shipment.user_id ='".$GLOBALS['user_id']."'";
	$sql =  execQuery($value);
	$row_count = sqlsrv_num_rows($sql);
	if($row_count > 0){
		return true;
	}
	else{
		return false;
	}
}

function ifDocumentExist($file_name,$file_type,$shipNumber,$ship_id){
	$value = "SELECT DISTINCT TOP 1 dbo.document.shipment_num FROM dbo.document WHERE dbo.document.shipment_id = '".$ship_id."' AND dbo.document.shipment_num ='".$shipNumber."' AND dbo.document.type ='".$file_type."'  AND dbo.document.name ='".$file_name."'";
	$sql =  execQuery($value);
	$row_count = sqlsrv_num_rows($sql);
	if($row_count > 0){
		return true;
	}
	else{
		return false;
	}
}

function getLastShipID($shipNumber,$user_id){
	$value = "SELECT DISTINCT TOP 1 dbo.shipment.id FROM dbo.shipment WHERE dbo.shipment.shipment_num = '".$shipNumber."' AND dbo.shipment.user_id ='".$user_id."'";
	$sql =  execQuery($value);
	$row_count = sqlsrv_num_rows($sql);
	if($row_count > 0){
		while ($row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
			return $ship_id = $row['id'];
		}
	}else{
		return false;
	}
}

function getCgmDoc($shipNumber,$ship_id){
	$value = "SELECT DISTINCT dbo.document.name as filename, * FROM dbo.document WHERE dbo.document.shipment_id = '".$ship_id."' AND dbo.document.shipment_num ='".$shipNumber."'";
	$sql =  execQuery($value);
	$cgm_document = array();
	$row_count = sqlsrv_num_rows($sql);
	if($row_count > 0){
		while ($row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
			$name =  $row["filename"];
			$add_document = ["ship_id"=>$row['shipment_id'],"shipment_num"=>$row['shipment_num'],"type"=>$row['type'],"file_name"=>$name,"path"=>$row['path']];
			array_push($cgm_document,$add_document);
		}
		return $cgm_document;
	}else{
		return $cgm_document;
	}
}

function logFile($logfiletype,$value){
	$filepath = 'E:/A2BFREIGHT_MANAGER/'.$GLOBALS['client_email'].'/';
	if (file_exists($filepath)) {
    		$myfile = fopen($filepath.$logfiletype, "a") or die("Unable to open file!");
			$txt = "Process Datetime: ".date("Y-m-d H:i:s")." ".$value;
			fwrite($myfile, "\n". $txt);
			fclose($myfile);
	} else {
	    	$myfile = fopen($filepath.$logfiletype, "w") or die("Unable to open file!");
			$txt = "Process Datetime: ".date("Y-m-d H:i:s")." ".$value;
			fwrite($myfile, "\n". $txt);
			fclose($myfile);
	}
}


### Delete files from File Manager
function removeShipmentFile($value){
	if(count($value)>0){
		foreach ($value as $key => $value_delete) {
			$ship_id = $value_delete['ship_id']; $shipment_num = $value_delete['shipment_num']; $file_name = $value_delete['file_name']; $type = $value_delete['type']; $path = $value_delete['path'];
		}
		$value = "DELETE FROM dbo.document WHERE dbo.document.shipment_id = '".$ship_id."' 
				  AND dbo.document.shipment_num ='".$shipment_num."'
				  AND dbo.document.name ='".$file_name."' 
				  AND dbo.document.type ='".$type."'";
		$res =  execQuery($value);
		### If result is success remove rawfile
		if($res){
				unlink($path);
			}
		}
}
