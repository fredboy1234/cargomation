<?php 
/**
* Class and Function List:
* Function list:
* - process_shipment()
* - getArrayName()
* - Base64_Decoder()
*/
/* PROCESS FLOW*/
require_once ('json.php');
require_once ('jsonpath-0.8.1.php');
require_once ('connection.php');
header('Content-Type: text/plain');
set_time_limit(0);
ini_set('memory_limit', '-1');
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

if(isset($_GET['user_id'])){
	
$CLIENT_ID = $_GET['user_id'];
$sqlSearchRecord = "SELECT TOP (1) * FROM [dbo].[user_webservice] WHERE [user_id] = '$CLIENT_ID' AND isactive='Y'";
$execRecord = sqlsrv_query($conn, $sqlSearchRecord);
$return = sqlsrv_has_rows($execRecord);


}
else
{
	$return = false;
}
if (empty($CLIENT_ID)) {
	$CLIENT_ID = "";
} 
if ($return === true) {
	while ($row_service = sqlsrv_fetch_array($execRecord, SQLSRV_FETCH_ASSOC)) {
		$webservicelink = $row_service['webservice_link'];
		$service_user = $row_service['webservice_username'];
		$service_password = $row_service['webservice_password'];
		$server_id = $row_service['server_id'];
		$enterprise_id = $row_service['enterprise_id'];
		$company_code = $row_service['company_code'];
		$auth = base64_encode($service_user . ":" . $service_password);
	}

	function process_shipment($key,$client_email,$ship_idlast,$webservicelink,$service_user,$service_password,$server_id,$enterprise_id,$auth,$company_code)
	{
try{		
		if(gethostname() == "A2B-Cargomation"){$db="a2bcargomation_db";}else{$db="a2bcargomation_db";}
		$serverName = "a2bserver.database.windows.net"; 
		$connectionInfo = array( "Database"=>$db, "UID"=>"A2B_Admin", "PWD"=>"v9jn9cQ9dF7W");
		$conn = sqlsrv_connect( $serverName, $connectionInfo);

		if ($conn) {} else {die(print_r(sqlsrv_errors(), true));}
		
		$curl_ = curl_init();
		curl_setopt_array(
			$curl_,
			array(
				CURLOPT_URL => $webservicelink,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => 
				"<UniversalDocumentRequest xmlns=\"http://www.cargowise.com/Schemas/Universal/2011/11\" version=\"1.1\">\r\n
				<DocumentRequest>\r\n
				<DataContext>\r\n
				<DataTargetCollection>\r\n
				<DataTarget>\r\n
				<Type>ForwardingShipment</Type>\r\n
				<Key>$key</Key>\r\n
				</DataTarget>\r\n
				</DataTargetCollection>\r\n
				<Company>\r\n
				<Code>$company_code</Code>\r\n
				</Company>\r\n
				<EnterpriseID>$enterprise_id</EnterpriseID>\r\n
				<ServerID>$server_id</ServerID>\r\n
				</DataContext>\r\n
				</DocumentRequest>\r\n
				</UniversalDocumentRequest>",
				CURLOPT_HTTPHEADER => array(
				"Authorization: Basic $auth",
				"Content-Type: application/xml",
				"Cookie: WEBSVC=f50e2886473c750f"
				)
			)
		);
		$parser = new __Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		curl_setopt($curl_, CURLOPT_SSL_VERIFYPEER, false);
		$document_request = curl_exec($curl_);
		curl_close($curl_);
		$xml_docs = simplexml_load_string($document_request);
		$json_documentrequest = json_encode($xml_docs, JSON_PRETTY_PRINT);
		$json_xpathdoc = json_decode($json_documentrequest, true);

		$doc_status = jsonPath($json_xpathdoc, "$.Status");
		$doc_status = $parser->encode($doc_status);
		$doc_status = node_exist(getArrayName($doc_status));
      
		if ($doc_status != "ERR") {
			//CHECK NUMBER OF ATTACHMENT
			$path_AttachedDocument = "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument";
			$xpath_attachedno = jsonPath($json_xpathdoc, $path_AttachedDocument.".FileName");
			$attachedno_parse = $parser->encode($xpath_attachedno);
			$attachedno = getArrayName($attachedno_parse);
			
			//IF FALSE MEANS MULTIPLE ATTACHMENT
			if ($attachedno == "false") {
				$xpath_attachedno = jsonPath($json_xpathdoc, $path_AttachedDocument);
				$json_documentrequest = trim(json_encode($xpath_attachedno, JSON_PRETTY_PRINT), "[]");
				$json_array  = json_decode($json_documentrequest, true);
				$file_count  = (int)count($json_array);
				$file_ctr  = 0;
			} else {
				$file_ctr = 1;
				$file_count = 1;
			}



			for ($attach = 0; $attach <= $file_count - 1; $attach++) {
				if ($file_ctr == 1) {
					
					$xpath_AttachedCountSingle = jsonPath($json_xpathdoc, $path_AttachedDocument.".FileName");
					$xpath_AttachedB64 = jsonPath($json_xpathdoc, $path_AttachedDocument.".ImageData");
					$xpath_DocType = jsonPath($json_xpathdoc, $path_AttachedDocument.".Type.Code");
					$xpath_SavedUtc = jsonPath($json_xpathdoc, $path_AttachedDocument.".SaveDateUTC");
					$xpath_SavedBy = jsonPath($json_xpathdoc, $path_AttachedDocument.".SavedBy.Code");
					$xpath_SavedEventTime = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.EventTime");
					$xpath_IsPublished = jsonPath($json_xpathdoc, $path_AttachedDocument.".IsPublished");
					$SingleAttach_ctr = $parser->encode($xpath_AttachedCountSingle);
					$SingleAttach_ctrb64 = $parser->encode($xpath_AttachedB64);
					$DocType = $parser->encode($xpath_DocType);
					$Saved_date = $parser->encode($xpath_SavedUtc);
					$Saved_EventTime = $parser->encode($xpath_SavedEventTime);
					$Saved_By = $parser->encode($xpath_SavedBy);
					$IsPublished = $parser->encode($xpath_IsPublished);
					//$ctr_1 = node_exist(getArrayName($SingleAttach_ctr));
					$ctr_1 = str_replace(['\\','/','#','+',':','*','?','"','<','>','|'],'',node_exist(getArrayName($SingleAttach_ctr)));
					$ctr_b64 = getArrayName($SingleAttach_ctrb64);
					$get_valDocType_ = node_exist(getArrayName($DocType));
					$get_valSavedDate = node_exist(getArrayName($Saved_date));
					$get_Saved_By = node_exist(getArrayName($Saved_By));
					$get_Saved_EventTime = node_exist(getArrayName($Saved_EventTime));
					$get_IsPublished = node_exist(getArrayName($IsPublished));
					
					//CHECK IF FILE IS FROM HUB OR CW IF HAS CHARACTER OF ~			
					$pos = strpos(strrev($get_Saved_By), '~', 1); 
					if($pos === false){
					$upload_src = "cargowise";
					}
					else{
					$upload_src = "hub";
					}
           
					$ifdocexist = "SELECT *,
					dbo.document_base64.img_data
					FROM   dbo.document_base64
					INNER JOIN dbo.document
					ON dbo.document_base64.document_id = dbo.document.id
					WHERE  dbo.document.NAME = '$ctr_1'
					/*AND dbo.document_base64.img_data = '$ctr_b64'*/
					AND dbo.document.type = '$get_valDocType_'
					AND dbo.document.shipment_id='$ship_idlast'
					";
					$ifdocexistqry = sqlsrv_query($conn, $ifdocexist);
					$ifdocexistres = sqlsrv_has_rows($ifdocexistqry);

					if ($ifdocexistres === false) {
					
					$sqlInsertRecord = "INSERT INTO document
					(shipment_id ,shipment_num, type, name, saved_by, saved_date, event_date, upload_src,is_published) Values
					($ship_idlast,'" . $key . "','" . $get_valDocType_ . "','" . $ctr_1 . "','" . $get_Saved_By . "','" . $get_valSavedDate . "','" . $get_Saved_EventTime . "','{$upload_src}','{$get_IsPublished}')";
						$execRecord = sqlsrv_query($conn, $sqlInsertRecord);
						//$sql_getlastdocID = "SELECT IDENT_CURRENT('dbo.document') as document_id;";
						//$execRecord_getlastdocID = sqlsrv_query($conn, $sql_getlastdocID);
						//while ($row_docid = sqlsrv_fetch_array($execRecord_getlastdocID, SQLSRV_FETCH_ASSOC)) {
						//	$doc_idlast1 = $row_docid['document_id'];
						//}

					 sqlsrv_next_result($execRecord);
				     sqlsrv_fetch($execRecord);
				     $doc_idlast1 = sqlsrv_get_field($execRecord, 0);
					
					//INSERT DOCUMENT IMG_DATA
						$sql_insertb641 = "INSERT INTO document_base64 (document_id,img_data) Values
			        ($doc_idlast1,'" . $ctr_b64 . "')";
						$sql_insertb641 = sqlsrv_query($conn, $sql_insertb641);
					
					//SET DEFAULT STATUS DOCUMENT
						$sql_insertdocstatus = "INSERT INTO document_status (document_id,status) Values
			        ($doc_idlast1,'pending')";
						$sql_insertdocstatus = sqlsrv_query($conn, $sql_insertdocstatus);

						Base64_Decoder($ctr_b64, $ctr_1, $client_email, $get_valDocType_, $key);
					}
				} 
				else
				{
					$xpath_AttachedCountSingle = jsonPath($json_xpathdoc, $path_AttachedDocument."[$attach].FileName");
					$xpath_AttachedB64 = jsonPath($json_xpathdoc, $path_AttachedDocument."[$attach].ImageData");
					$xpath_DocType = jsonPath($json_xpathdoc,$path_AttachedDocument."[$attach].Type.Code");
					$xpath_SavedUtc = jsonPath($json_xpathdoc, $path_AttachedDocument."[$attach].SaveDateUTC");
					$xpath_SavedBy = jsonPath($json_xpathdoc, $path_AttachedDocument."[$attach].SavedBy.Code");
					$xpath_IsPublished = jsonPath($json_xpathdoc, $path_AttachedDocument."[$attach].IsPublished");
					$xpath_SavedEventTime = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.EventTime");
					$SingleAttach_ctr = $parser->encode($xpath_AttachedCountSingle);
					$SingleAttach_ctrb64 = $parser->encode($xpath_AttachedB64);
					$DocType = $parser->encode($xpath_DocType);
					$Saved_date = $parser->encode($xpath_SavedUtc);
					$Saved_EventTime = $parser->encode($xpath_SavedEventTime);
					$Saved_By = $parser->encode($xpath_SavedBy);
					$IsPublished = $parser->encode($xpath_IsPublished);
					//$ctr_1 = node_exist(getArrayName($SingleAttach_ctr));
					$ctr_1 = str_replace(['\\','#','+','/',':','*','?','"','<','>','|'],'',node_exist(getArrayName($SingleAttach_ctr)));
					$ctr_b64 = node_exist(getArrayName($SingleAttach_ctrb64));
					$get_valDocType_ = node_exist(getArrayName($DocType));
					$get_valSavedDate = node_exist(getArrayName($Saved_date));
					$get_Saved_By = node_exist(getArrayName($Saved_By));
					$get_Saved_EventTime = node_exist(getArrayName($Saved_EventTime));
					$get_IsPublished = node_exist(getArrayName($IsPublished));
					
					//CHECK IF FILE IS FROM HUB OR CW IF HAS CHARACTER OF ~			
					$pos = strpos(strrev($get_Saved_By), '~', 1); 
					if($pos === false){
					$upload_src = "cargowise";
					}
					else{
					$upload_src = "hub";
					}
					
					$ifdocexist_ = "SELECT *,
					dbo.document_base64.img_data
					FROM   dbo.document_base64
					INNER JOIN dbo.document
					ON dbo.document_base64.document_id = dbo.document.id
					WHERE  dbo.document.NAME = '$ctr_1'
					/*AND dbo.document_base64.img_data = '$ctr_b64'*/
					AND dbo.document.type = '$get_valDocType_'
					AND dbo.document.shipment_id='$ship_idlast'";
					
					$ifdocexistqry_ = sqlsrv_query($conn, $ifdocexist_);
					$ifdocexistres_ = sqlsrv_has_rows($ifdocexistqry_);

					if ($ifdocexistres_ === false) {
						$sqlInsertRecord = "INSERT INTO document
						(shipment_id ,shipment_num, type, name, saved_by, saved_date, event_date, upload_src,is_published) Values
						({$ship_idlast},'" . $key . "','" . $get_valDocType_ . "','" . $ctr_1 . "','" . $get_Saved_By . "','" . $get_valSavedDate . "','" . $get_Saved_EventTime . "','{$upload_src}','{$get_IsPublished}') SELECT SCOPE_IDENTITY() as ins_id ";
						//$execRecord = sqlsrv_query($conn, $sqlInsertRecord);
						//$sql_getlastdocID = "SELECT SCOPE_IDENTITY() as ins_id ";
						$execRecord_getlastdocID = sqlsrv_query($conn, $sqlInsertRecord);
						//while ($row_docid = sqlsrv_fetch_array($execRecord_getlastdocID, SQLSRV_FETCH_ASSOC)) {
							// $doc_idlast1 = $row_docid['ins_id'];
						//}
						//echo $doc_idlast1;die();

					 sqlsrv_next_result($execRecord_getlastdocID);
				     sqlsrv_fetch($execRecord_getlastdocID);
				     $doc_idlast1 = sqlsrv_get_field($execRecord_getlastdocID, 0);

				
					//INSERT DOCUMENT IMG_DATA
						$sql_insertb641 = "INSERT INTO document_base64 (document_id,img_data) Values
			        ($doc_idlast1,'" . $ctr_b64 . "')";
						$sql_insertb641 = sqlsrv_query($conn, $sql_insertb641);
					
					//SET DEFAULT STATUS DOCUMENT
						$sql_insertdocstatus = "INSERT INTO document_status (document_id,status) Values
			        ($doc_idlast1,'pending')";
						$sql_insertdocstatus = sqlsrv_query($conn, $sql_insertdocstatus);
						Base64_Decoder($ctr_b64, $ctr_1, $client_email, $get_valDocType_, $key);
						}
					else{
						Base64_Decoder($ctr_b64, $ctr_1, $client_email, $get_valDocType_, $key);  
					}
				}
			}
		} else 
		{
			echo "no edocs found";
		}
	}
	catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
	} 
}
	// END OF DOCUMENT PROCESS
	
	
	function getArrayName($val)
	{
		return str_replace(
			array(
				'["',
				'"]',
				'\"',
				'"',
				'[',
				']',
				'\/'
			),
			array(
				"",
				"",
				"",
				"",
				"",
				"",
				"/"
			),
			$val
		);
	}
	
	function file_log($shipment_key,$path,$client_id){
	try{
		if(gethostname() == "A2B-Cargomation"){$db="a2bcargomation_db";}else{$db="a2bfreighthub_db";}
		$serverName = "a2bserver.database.windows.net"; 
		$connectionInfo = array( "Database"=>$db, "UID"=>"A2B_Admin", "PWD"=>"v9jn9cQ9dF7W");
		$conn = sqlsrv_connect( $serverName, $connectionInfo);

		if ($conn) {} else {die(print_r(sqlsrv_errors(), true));}
		$date_added = date("Ymd");
		$sql = "INSERT INTO inbound_xml (user_id,filename,shipment_num,date_added) VALUES ('$client_id','$path','$shipment_key','$date_added')";
		$exec_file_log = sqlsrv_query($conn, $sql);
		
		}
		catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		} 
	} 
	
	function node_exist($value){
		if($value == 'false'){
			$value = "";
		}
		return $value;
	}
 
	function Base64_Decoder($val, $valName, $id, $pathName, $shipkey){
	try{
		$path = "E:/A2BFREIGHT_MANAGER/$id/CW_FILE/$shipkey/$pathName/";
		$b64 = str_replace(array('["', '"]', '\/'), array("", "", "/"), $val);
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
			file_put_contents($path . $valName, base64_decode($b64));
		} else {
			file_put_contents($path . $valName, base64_decode($b64));
		}
	}
	catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	$sqluser_info = "SELECT TOP (1) * FROM [dbo].[user_info] WHERE [user_id] = '$CLIENT_ID'";
	$execRecord_userinfo = sqlsrv_query($conn, $sqluser_info);
	$return_user = sqlsrv_has_rows($execRecord_userinfo);
	if ($return_user == true) {
		while ($row_user = sqlsrv_fetch_array($execRecord_userinfo, SQLSRV_FETCH_ASSOC)) {
			$client_email = $row_user['email'];
		    $_GET['get_email'] = $client_email;
		}
		$myarray = glob("E:/A2BFREIGHT_MANAGER/$client_email/CW_XML/*.xml");
		//usort($myarray, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));
		usort($myarray, fn($a, $b) => filemtime($a) - filemtime($b));
		foreach ($myarray as $filename) {
			$path_DataSource ="$.Body.UniversalShipment.Shipment.DataContext.DataSourceCollection.DataSource";
			$CONSOLNUMBER = "";
			$SHIPMENTKEY = "";
			$SHIPMENTTYPE_ ="";
			$WAYBILLNUMBER = "";
			$HOUSEWAYBILLNUMBER = "";
			$TRANSMODE = "";
			$VESSELNAME = "";
			$VOYAGEFLIGHTNO = "";
			$VESSELLOYDSIMO = "";
			$TRANS_ETA = "";
			$TRANS_ETD = "";
			$ACTUAL_ARRIVAL="";
			$ACTUAL_DEPARTURE="";
			$PLACEOFDELIVERY = "";
			$PLACEOFRECEIPT = "";
			$CONSIGNEE = "";
			$CONSIGNOR = "";
			$PATH_SENDINGAGENT = "";
			$PATH_RECEIVINGAGENT = "";
			$RECEIVINGAGENTADDRESS = "";
			$SENDINGAGENTADDRESS = "";
			$CONSIGNEEADDRESS = "";
			$CONSIGNORADDRESS ="";
			$PATH_CONSIGNORADDRESS = "";
			$ship_idlast="";
			$PORTOFDISCHARGE="";
			$PORTOFLOADING="";
			$TOTALVOLUME="";
			$TOTALHEIGHT="";
			$TOTALWIDTH="";
			$TOTALLENGTH="";
	
			$parser = new __Services_JSON(SERVICES_JSON_LOOSE_TYPE);
			$myxmlfilecontent = file_get_contents($filename);
			$xml = simplexml_load_string($myxmlfilecontent);
			$universalshipment = json_encode($xml, JSON_PRETTY_PRINT);
			$universal_shipment = json_decode($universalshipment, true);

			$XPATH_SHIPMENTTYPE = jsonPath($universal_shipment, $path_DataSource.".Type");
			$SHIPMENTTYPE = $parser->encode($XPATH_SHIPMENTTYPE);
		    $SHIPMENTTYPE = node_exist(getArrayName($SHIPMENTTYPE));

		    $path_EventSource ="$.Body.UniversalEvent.Event.DataContext.EventType";
		    $XPATH_EVENTYPE = jsonPath($universal_shipment, $path_EventSource.".Description");
			$EVENTYPE = $parser->encode($XPATH_EVENTYPE);
		    $EVENTYPE = node_exist(getArrayName($EVENTYPE));
			
		    $SHIPKEY = false;
		    $CONSOLKEY = false;
		    $CUSTOMEKEY = false;

			if ($SHIPMENTTYPE != "") {
				if ($SHIPMENTTYPE == "ForwardingShipment") {
					$XPATH_SHIPMENTKEY = jsonPath($universal_shipment, $path_DataSource.".Key");
					$SHIPMENTKEY = $parser->encode($XPATH_SHIPMENTKEY);
					$SHIPMENTKEY = node_exist(getArrayName($SHIPMENTKEY));
					$SHIPKEY = true;
				} elseif ($SHIPMENTTYPE == "ForwardingConsol") {
					$XPATH_CONSOLNUMBER = jsonPath($universal_shipment, $path_DataSource.".Key");
					$CONSOLNUMBER = $parser->encode($XPATH_CONSOLNUMBER);
					$CONSOLNUMBER = node_exist(getArrayName($CONSOLNUMBER));
					$CONSOLKEY = true;
				}
				elseif ($SHIPMENTTYPE == "CustomsDeclaration") {
					$XPATH_SHIPMENTKEY = jsonPath($universal_shipment, $path_DataSource.".Key");
					$SHIPMENTKEY = $parser->encode($XPATH_SHIPMENTKEY);
					$SHIPMENTKEY = node_exist(getArrayName($SHIPMENTKEY));
					$CUSTOMKEY = true;
				}
				
				else {
					if ($CONSOLNUMBER == "" || is_null($CONSOLNUMBER) == true) {
						$CONSOLNUMBER = "";

					}
				}
			} elseif ($SHIPMENTTYPE == "") {

				for ($k = 0; $k <= 2; $k++) {
					$XPATH_SHIPMENTTYPE_ = jsonPath($universal_shipment, $path_DataSource."[$k].Type");
					$SHIPMENTTYPE_ = $parser->encode($XPATH_SHIPMENTTYPE_);
					$SHIPMENTTYPE_ = node_exist(getArrayName($SHIPMENTTYPE_));

					if ($SHIPMENTTYPE_ != "") {

						if ($SHIPMENTTYPE_ == "ForwardingShipment") {
							$XPATH_SHIPMENTKEY = jsonPath($universal_shipment, $path_DataSource."[$k].Key");
							$SHIPMENTKEY = $parser->encode($XPATH_SHIPMENTKEY);
							$SHIPMENTKEY = node_exist(getArrayName($SHIPMENTKEY));
							$SHIPKEY = true;
								

						} elseif ($SHIPMENTTYPE_ == "ForwardingConsol") {
							$XPATH_CONSOLNUMBER = jsonPath($universal_shipment, $path_DataSource."[$k].Key");
							$CONSOLNUMBER = $parser->encode($XPATH_CONSOLNUMBER);
							$CONSOLNUMBER = node_exist(getArrayName($CONSOLNUMBER));
							$CONSOLKEY = true;
								
						}
						elseif ($SHIPMENTTYPE_ == "CustomsDeclaration") {
							$XPATH_SHIPMENTKEY = jsonPath($universal_shipment, $path_DataSource."[$k].Key");
							$SHIPMENTKEY = $parser->encode($XPATH_SHIPMENTKEY);
							$SHIPMENTKEY = node_exist(getArrayName($SHIPMENTKEY));
							$CUSTOMKEY = true;
							
						}
						  
					} else {
					
						if ($SHIPMENTKEY == "") {
							$SHIPMENTKEY = "";
								
						}
						if ($CONSOLNUMBER == "" || is_null($CONSOLNUMBER) == true) {
							$CONSOLNUMBER = "";
								
						}
                    
                    if($SHIPKEY == false || $CONSOLKEY == false || $CONSOLKEY == false)
                    {
					$destination_pathERR = "E:/A2BFREIGHT_MANAGER/$client_email/CW_ERROR/";						
					if(!file_exists($destination_pathERR.$filename)){
					rename($filename, $destination_pathERR . pathinfo($filename, PATHINFO_BASENAME));
					}
							Continue;
						}
					}
				}
			}
	         

	         //require_once('order.php');


			if ($CONSOLNUMBER == "" || $CONSOLNUMBER != "") {

				//XML PATH
				$path_UniversalShipment = "$.Body.UniversalShipment.Shipment";
				$path_UniversalShipmentContext = "$.Body.UniversalShipment.Shipment.DataContext";
				$path_UniversalSubShipment = "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment";
				$path_SubUniversalSubShipment = "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection";
				$path_AddressUniversalShipment = "$.Body.UniversalShipment.Shipment.OrganizationAddressCollection";
				$path_TransportLegCollection = "$.Body.UniversalShipment.Shipment.TransportLegCollection.TransportLeg";
				$path_ContainerCollection = "$.Body.UniversalShipment.Shipment.ContainerCollection.Container";
				$path_OrderNumberCollection ="$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.RelatedShipmentCollection.RelatedShipment.Order";
				$path_PackingLineCollection ="$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.PackingLineCollection.PackingLine";
				
				if($CONSOLNUMBER == ""){
					$path_SubUniversalSubShipment = "$.Body.UniversalShipment.Shipment.OrganizationAddressCollection"; 
				}
				else{
					$path_SubUniversalSubShipment = "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection";
				 }
				$items = array();

				//GET ESTIMATED DELIVERY DATE NUMBER
				$XPATH_WAYBILLNUMBER = jsonPath($universal_shipment, $path_UniversalShipment.".LocalProcessing.EstimatedDelivery");
				$WAYBILLNUMBER = $parser->encode($XPATH_WAYBILLNUMBER);
				$WAYBILLNUMBER = node_exist(getArrayName($WAYBILLNUMBER));

				//GET WAYBLL NUMBER
				$XPATH_WAYBILLNUMBER = jsonPath($universal_shipment, $path_UniversalShipment.".WayBillNumber");
				$WAYBILLNUMBER = $parser->encode($XPATH_WAYBILLNUMBER);
				$WAYBILLNUMBER = node_exist(getArrayName($WAYBILLNUMBER));
                //GET HOUSEBILL NUMBER
				$XPATH_HOUSEWAYBILLNUMBER = jsonPath($universal_shipment, $path_UniversalSubShipment.".WayBillNumber");
				$HOUSEWAYBILLNUMBER = $parser->encode($XPATH_HOUSEWAYBILLNUMBER);
				$HOUSEWAYBILLNUMBER = node_exist(getArrayName($HOUSEWAYBILLNUMBER));
                //GET TRANSPORT MODE
				$OrganizationAddress = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress");
				$OrganizationAddress_ctr = $OrganizationAddress;
				
				//GET CONTAINERMODE
				$XPATH_CONTAINERMODE= jsonPath($universal_shipment, $path_UniversalShipment.".ContainerMode.Code");
				$CONTAINERMODE = $parser->encode($XPATH_CONTAINERMODE);
				$CONTAINERMODE = node_exist(getArrayName($CONTAINERMODE));
				
				//GET TRANSPORTMODE
				$XPATH_TRANSMODE = jsonPath($universal_shipment, $path_TransportLegCollection.".TransportMode");
				$TRANSMODE = $parser->encode($XPATH_TRANSMODE);
				$TRANSMODE = node_exist(getArrayName($TRANSMODE));
				
				//GET ETD
				$XPATH_SHIP_ETD = jsonPath($universal_shipment, $path_TransportLegCollection.".EstimatedDeparture");
				$TRANS_ETD = $parser->encode($XPATH_SHIP_ETD);
				$TRANS_ETD = node_exist(getArrayName($TRANS_ETD));
				
				//GET ETA
				$XPATH_SHIP_ETA = jsonPath($universal_shipment, $path_TransportLegCollection.".EstimatedArrival");
				$TRANS_ETA = $parser->encode($XPATH_SHIP_ETA);
				$TRANS_ETA = node_exist(getArrayName($TRANS_ETA));

				//GET ACTUAL ARRIVAL
				$XPATH_ACTUAL_ARRIVAL = jsonPath($universal_shipment, $path_TransportLegCollection.".ActualArrival");
				$ACTUAL_ARRIVAL = $parser->encode($XPATH_ACTUAL_ARRIVAL);
				$ACTUAL_ARRIVAL = node_exist(getArrayName($ACTUAL_ARRIVAL));

				//GET ACTUAL DEPARTURE
				$XPATH_ACTUAL_DEPARTURE = jsonPath($universal_shipment, $path_TransportLegCollection.".ActualDeparture");
				$ACTUAL_DEPARTURE = $parser->encode($XPATH_ACTUAL_DEPARTURE);
				$ACTUAL_DEPARTURE = node_exist(getArrayName($ACTUAL_DEPARTURE));

				//GET ORDER NUMBER
				$XPATH_ORDER_NUMBER = jsonPath($universal_shipment, $path_OrderNumberCollection.".OrderNumber");
				$ORDER_NUMBER = $parser->encode($XPATH_ORDER_NUMBER);
				$ORDER_NUMBER = node_exist(getArrayName($ORDER_NUMBER));
				
				//GET TRIGGER DATE
				$XPATH_SHIP_TRIGGERDATE = jsonPath($universal_shipment, $path_UniversalShipmentContext.".TriggerDate");
				$SHIP_TRIGGERDATE = $parser->encode($XPATH_SHIP_TRIGGERDATE);
				$SHIP_TRIGGERDATE = node_exist(getArrayName($SHIP_TRIGGERDATE));
				if($SHIP_TRIGGERDATE == null){
					$SHIP_TRIGGERDATE = date("Y-m-d h:i:s");
				}
                //GET VESSELLOYDSIMO
				$XPATH_VESSELLOYDSIMO = jsonPath($universal_shipment, $path_TransportLegCollection.".VesselLloydsIMO");
				$VESSELLOYDSIMO = $parser->encode($XPATH_VESSELLOYDSIMO);
				$VESSELLOYDSIMO = node_exist(getArrayName($VESSELLOYDSIMO));

				//GET VESSEL NAME
				$XPATH_VESSELNAME = jsonPath($universal_shipment, $path_UniversalShipment.".VesselName");
				$VESSELNAME = $parser->encode($XPATH_VESSELNAME);
				$VESSELNAME = node_exist(getArrayName($VESSELNAME));
                //GET VOYAGE#
				$XPATH_VOYAGEFLIGHTNO = jsonPath($universal_shipment, $path_UniversalShipment.".VoyageFlightNo");
				$VOYAGEFLIGHTNO = $parser->encode($XPATH_VOYAGEFLIGHTNO);
				$VOYAGEFLIGHTNO = node_exist(getArrayName($VOYAGEFLIGHTNO));

				//GET TOTAL VOLUME OF CONTAINER
				$XPATH_TOTALVOLUME = jsonPath($universal_shipment, $path_UniversalShipment.".TotalVolume");
				$TOTALVOLUME = $parser->encode($XPATH_TOTALVOLUME);
				$TOTALVOLUME = node_exist(getArrayName($TOTALVOLUME));

                //GET PLACEOFDELIVERY
				$XPATH_PLACEOFDELIVERY = jsonPath($universal_shipment, $path_UniversalShipment.".PlaceOfDelivery.Name");
				$PLACEOFDELIVERY = $parser->encode($XPATH_PLACEOFDELIVERY);
				$PLACEOFDELIVERY = node_exist(getArrayName($PLACEOFDELIVERY));
                //GET PLACEOFRECEIPT
				$XPATH_PLACEOFRECEIPT = jsonPath($universal_shipment, $path_UniversalShipment.".PlaceOfReceipt.Name");
				$PLACEOFRECEIPT = $parser->encode($XPATH_PLACEOFRECEIPT);
				$PLACEOFRECEIPT = node_exist(getArrayName($PLACEOFRECEIPT));
				$PLACEOFRECEIPT = str_replace("'", "", $PLACEOFRECEIPT);
                //GET CONTAINER COUNT
				$XPATH_CONTAINERCOUNT = jsonPath($universal_shipment, $path_UniversalShipment.".ContainerCount");
				$CONTAINERCOUNT = $parser->encode($XPATH_CONTAINERCOUNT);
				$CONTAINERctr = node_exist(getArrayName($CONTAINERCOUNT));
				$CONTAINERctr = (int)$CONTAINERctr;

				$XPATH_PORTOFLOADING = jsonPath($universal_shipment, $path_UniversalShipment.".PortOfLoading.Name");
				$PORTOFLOADING = $parser->encode($XPATH_PORTOFLOADING);
				$PORTOFLOADING = node_exist(getArrayName($PORTOFLOADING));
				$PORTOFLOADING = str_replace("'", "", $PORTOFLOADING);

				$XPATH_PORTOFDISCHARGE = jsonPath($universal_shipment, $path_UniversalShipment.".PortOfDischarge.Name");
				$PORTOFDISCHARGE = $parser->encode($XPATH_PORTOFDISCHARGE);
				$PORTOFDISCHARGE = node_exist(getArrayName($PORTOFDISCHARGE));

				$XPATH_PACKCONTAINER = jsonPath($universal_shipment, $path_PackingLineCollection.".ContainerNumber");
				$XPATH_PACKCONTAINER = $parser->encode($XPATH_PACKCONTAINER);
				$PACKCONTAINER = node_exist(getArrayName($XPATH_PACKCONTAINER));

				$XPATH_PACKVOL = jsonPath($universal_shipment, $path_PackingLineCollection.".VolumeUnit.Code");
				$XPATH_PACKVOL = $parser->encode($XPATH_PACKVOL);
				$PACKVOL = node_exist(getArrayName($XPATH_PACKVOL));

				$XPATH_PACKVOLDESC = jsonPath($universal_shipment, $path_PackingLineCollection.".VolumeUnit.Description");
				$XPATH_PACKVOLDESC = $parser->encode($XPATH_PACKVOLDESC);
				$VOLDESC = node_exist(getArrayName($XPATH_PACKVOLDESC));


				$pack_collection = array(); 
				$pack_collection[] = array("ContainerNumber"=>$PACKCONTAINER,"VolumeUnit"=>$PACKVOL,"Description"=>$VOLDESC);	

				 $pack_line = json_encode($pack_collection);

				
				if ($XPATH_TRANSMODE == "" || $XPATH_SHIP_ETD == "" || $XPATH_SHIP_ETA == "") {        
              	
                for ($k = 0; $k <= 5; $k++) {
               	$XPATH_TRANSMODE = jsonPath($universal_shipment, $path_TransportLegCollection."[$k].LegOrder");
				$TRANSMODE_LEG = $parser->encode($XPATH_TRANSMODE);
				$LEG_ORDER = node_exist(getArrayName($TRANSMODE_LEG));
				if ($TRANSMODE_LEG != 'false') {
					//GET TRANS MODE
                    $XPATH_TRANSMODE = jsonPath($universal_shipment, $path_TransportLegCollection."[$k].TransportMode");
					$TRANSMODE = $parser->encode($XPATH_TRANSMODE);
					$TRANSMODE = node_exist(getArrayName($TRANSMODE));
					//GET ETD
					$XPATH_SHIP_ETD = jsonPath($universal_shipment, $path_TransportLegCollection."[$k].EstimatedDeparture");
					$TRANS_ETD = $parser->encode($XPATH_SHIP_ETD);
					$TRANS_ETD = node_exist(getArrayName($TRANS_ETD));
					//GET ETA
					$XPATH_SHIP_ETA = jsonPath($universal_shipment, $path_TransportLegCollection."[$k].EstimatedArrival");
					$TRANS_ETA = $parser->encode($XPATH_SHIP_ETA);
					$TRANS_ETA = node_exist(getArrayName($TRANS_ETA));

					//GET ACTUAL ARRIVAL
					$XPATH_ACTUAL_ARRIVAL = jsonPath($universal_shipment, $path_TransportLegCollection."[$k].ActualArrival");
					$ACTUAL_ARRIVAL = $parser->encode($XPATH_ACTUAL_ARRIVAL);
					$ACTUAL_ARRIVAL = node_exist(getArrayName($ACTUAL_ARRIVAL));

                    //GET VESSEL LLOYDS
					$XPATH_VESSELLOYDSIMO = jsonPath($universal_shipment, $path_TransportLegCollection."[$k].VesselLloydsIMO");
					$VESSELLOYDSIMO = $parser->encode($XPATH_VESSELLOYDSIMO);
					$VESSELLOYDSIMO = node_exist(getArrayName($VESSELLOYDSIMO));

					//GET VESSEL LEG TYPE
					$XPATH_TRANSLEGTYPE = jsonPath($universal_shipment, $path_TransportLegCollection."[$k].LegType");
					$XPATH_TRANSLEGTYPE = $parser->encode($XPATH_TRANSLEGTYPE);
					$LEG_TYPE = node_exist(getArrayName($XPATH_TRANSLEGTYPE));

					//GET VESSEL NAME TRANSPORT
					$XPATH_TRANSVESSELNAME = jsonPath($universal_shipment, $path_TransportLegCollection."[$k].VesselName");
					$XPATH_TRANSVESSELNAME = $parser->encode($XPATH_TRANSVESSELNAME);
					$TRANSVESSELNAME = node_exist(getArrayName($XPATH_TRANSVESSELNAME));

					//GET DISCHARGE NAME
					$XPATH_TRANSDISCHARGE = jsonPath($universal_shipment, $path_TransportLegCollection."[$k].PortOfDischarge.Name");
					$XPATH_TRANSDISCHARGE = $parser->encode($XPATH_TRANSDISCHARGE);
					$TRANSDISCHARGE = node_exist(getArrayName($XPATH_TRANSDISCHARGE));
					$TRANSDISCHARGE = str_replace("'", "", $TRANSDISCHARGE);

					//GET LOADING NAME
					$XPATH_TRANSLOADING = jsonPath($universal_shipment, $path_TransportLegCollection."[$k].PortOfLoading.Name");
					$XPATH_TRANSLOADING = $parser->encode($XPATH_TRANSLOADING);
					$TRANSLOADING = node_exist(getArrayName($XPATH_TRANSLOADING));
					$TRANSLOADING = str_replace("'", "", $TRANSLOADING);


					//GET BOOKING CODE
					$XPATH_BOOKINGSTATUS = jsonPath($universal_shipment, $path_TransportLegCollection."[$k].BookingStatus.Code");
					$XPATH_BOOKINGSTATUS = $parser->encode($XPATH_BOOKINGSTATUS);
					$BOOKINGSTATUS = node_exist(getArrayName($XPATH_BOOKINGSTATUS));
					
					//GET BOOKING DESC
					$XPATH_BOOKINGDESC= jsonPath($universal_shipment, $path_TransportLegCollection."[$k].BookingStatus.Description");
					$XPATH_BOOKINGDESC = $parser->encode($XPATH_BOOKINGDESC);
					$BOOKINGDESC = node_exist(getArrayName($XPATH_BOOKINGDESC));

					//GET CARRIER DETAILS
					$XPATH_CARRIERTYPE= jsonPath($universal_shipment, $path_TransportLegCollection."[$k].Carrier.AddressType");
					$XPATH_CARRIERTYPE = $parser->encode($XPATH_CARRIERTYPE);
					$CARRIERTYPE = node_exist(getArrayName($XPATH_CARRIERTYPE));

					//GET CARRIER COMPANY NAME
					$XPATH_CARRIERNAME= jsonPath($universal_shipment, $path_TransportLegCollection."[$k].Carrier.CompanyName");
					$XPATH_CARRIERNAME = $parser->encode($XPATH_CARRIERNAME);
					$CARRIERNAME = node_exist(getArrayName($XPATH_CARRIERNAME));

					//GET CARRIER COMPANY NAME
					$XPATH_CARRIERORG= jsonPath($universal_shipment, $path_TransportLegCollection."[$k].Carrier.OrganizationCode");
					$XPATH_CARRIERORG = $parser->encode($XPATH_CARRIERORG);
					$CARRIERORG = node_exist(getArrayName($XPATH_CARRIERORG));

					//GET LCL DATE AVAILABILITY
					$XPATH_LCLAvailability= jsonPath($universal_shipment, $path_TransportLegCollection."[$k].LCLAvailability");
					$XPATH_LCLAvailability = $parser->encode($XPATH_LCLAvailability);
					$LCLAvailability = node_exist(getArrayName($XPATH_LCLAvailability));

					//GET LCL DATE STORAGE
					$XPATH_LCLStorageDate= jsonPath($universal_shipment, $path_TransportLegCollection."[$k].LCLStorageDate");
					$XPATH_LCLStorageDate = $parser->encode($XPATH_LCLStorageDate);
					$LCLStorageDate = node_exist(getArrayName($XPATH_LCLStorageDate));

					$items[] = array("LegOrder"=>$LEG_ORDER,"LegType"=>$LEG_TYPE,"VesselName"=>$TRANSVESSELNAME,"Destination"=>$TRANSDISCHARGE,"Origin"=>$TRANSLOADING,"ETA"=>$TRANS_ETA,"ETD"=>$TRANS_ETD,"BookingStatus"=>$BOOKINGSTATUS,"BookingDesc"=>$BOOKINGDESC,"AddressType"=>$CARRIERTYPE,"CarrierName"=>$CARRIERNAME,"CarrierOrg"=>$CARRIERORG,"LCLAvailability"=>$LCLAvailability,"LCLStorageDate"=>$LCLStorageDate);
                    
				 }
				 else
				 {
				 	break;
				 		}
                 	}	
				}
				else{
					//GET TRANS MODE
                    $XPATH_TRANSMODE = jsonPath($universal_shipment, $path_TransportLegCollection.".TransportMode");
					$TRANSMODE = $parser->encode($XPATH_TRANSMODE);
					$TRANSMODE = node_exist(getArrayName($TRANSMODE));
					//GET ETD
					$XPATH_SHIP_ETD = jsonPath($universal_shipment, $path_TransportLegCollection.".EstimatedDeparture");
					$TRANS_ETD = $parser->encode($XPATH_SHIP_ETD);
					$TRANS_ETD = node_exist(getArrayName($TRANS_ETD));
					//GET ETA
					$XPATH_SHIP_ETA = jsonPath($universal_shipment, $path_TransportLegCollection.".EstimatedArrival");
					$TRANS_ETA = $parser->encode($XPATH_SHIP_ETA);
					$TRANS_ETA = node_exist(getArrayName($TRANS_ETA));

					//GET ACTUAL ARRIVAL
					$XPATH_ACTUAL_ARRIVAL = jsonPath($universal_shipment, $path_TransportLegCollection.".ActualArrival");
					$ACTUAL_ARRIVAL = $parser->encode($XPATH_ACTUAL_ARRIVAL);
					$ACTUAL_ARRIVAL = node_exist(getArrayName($ACTUAL_ARRIVAL));

                    //GET VESSEL LLOYDS
					$XPATH_VESSELLOYDSIMO = jsonPath($universal_shipment, $path_TransportLegCollection.".VesselLloydsIMO");
					$VESSELLOYDSIMO = $parser->encode($XPATH_VESSELLOYDSIMO);
					$VESSELLOYDSIMO = node_exist(getArrayName($VESSELLOYDSIMO));

					//GET VESSEL LEG TYPE
					$XPATH_TRANSLEGTYPE = jsonPath($universal_shipment, $path_TransportLegCollection.".LegType");
					$XPATH_TRANSLEGTYPE = $parser->encode($XPATH_TRANSLEGTYPE);
					$LEG_TYPE = node_exist(getArrayName($XPATH_TRANSLEGTYPE));

					//GET VESSEL NAME TRANSPORT
					$XPATH_TRANSVESSELNAME = jsonPath($universal_shipment, $path_TransportLegCollection.".VesselName");
					$XPATH_TRANSVESSELNAME = $parser->encode($XPATH_TRANSVESSELNAME);
					$TRANSVESSELNAME = node_exist(getArrayName($XPATH_TRANSVESSELNAME));

					//GET DISCHARGE NAME
					$XPATH_TRANSDISCHARGE = jsonPath($universal_shipment, $path_TransportLegCollection.".PortOfDischarge.Name");
					$XPATH_TRANSDISCHARGE = $parser->encode($XPATH_TRANSDISCHARGE);
					$TRANSDISCHARGE = node_exist(getArrayName($XPATH_TRANSDISCHARGE));
					$TRANSDISCHARGE = str_replace("'", "", $TRANSDISCHARGE);

					//GET LOADING NAME
					$XPATH_TRANSLOADING = jsonPath($universal_shipment, $path_TransportLegCollection.".PortOfLoading.Name");
					$XPATH_TRANSLOADING = $parser->encode($XPATH_TRANSLOADING);
					$TRANSLOADING = node_exist(getArrayName($XPATH_TRANSLOADING));
					$TRANSLOADING = str_replace("'", "", $TRANSLOADING);

					//GET BOOKING CODE
					$XPATH_BOOKINGSTATUS = jsonPath($universal_shipment, $path_TransportLegCollection.".BookingStatus.Code");
					$XPATH_BOOKINGSTATUS = $parser->encode($XPATH_BOOKINGSTATUS);
					$BOOKINGSTATUS = node_exist(getArrayName($XPATH_BOOKINGSTATUS));
					
					//GET BOOKING DESC
					$XPATH_BOOKINGDESC= jsonPath($universal_shipment, $path_TransportLegCollection.".BookingStatus.Description");
					$XPATH_BOOKINGDESC = $parser->encode($XPATH_BOOKINGDESC);
					$BOOKINGDESC = node_exist(getArrayName($XPATH_BOOKINGDESC));

					//GET CARRIER DETAILS
					$XPATH_CARRIERTYPE= jsonPath($universal_shipment, $path_TransportLegCollection.".Carrier.AddressType");
					$XPATH_CARRIERTYPE = $parser->encode($XPATH_CARRIERTYPE);
					$CARRIERTYPE = node_exist(getArrayName($XPATH_CARRIERTYPE));

					//GET CARRIER COMPANY NAME
					$XPATH_CARRIERNAME= jsonPath($universal_shipment, $path_TransportLegCollection.".Carrier.CompanyName");
					$XPATH_CARRIERNAME = $parser->encode($XPATH_CARRIERNAME);
					$CARRIERNAME = node_exist(getArrayName($XPATH_CARRIERNAME));

					//GET CARRIER COMPANY NAME
					$XPATH_CARRIERORG= jsonPath($universal_shipment, $path_TransportLegCollection.".Carrier.OrganizationCode");
					$XPATH_CARRIERORG = $parser->encode($XPATH_CARRIERORG);
					$CARRIERORG = node_exist(getArrayName($XPATH_CARRIERORG));

					//GET LCL DATE AVAILABILITY
					$XPATH_LCLAvailability= jsonPath($universal_shipment, $path_TransportLegCollection.".LCLAvailability");
					$XPATH_LCLAvailability = $parser->encode($XPATH_LCLAvailability);
					$LCLAvailability = node_exist(getArrayName($XPATH_LCLAvailability));

					//GET LCL DATE STORAGE
					$XPATH_LCLStorageDate= jsonPath($universal_shipment, $path_TransportLegCollection.".LCLStorageDate");
					$XPATH_LCLStorageDate = $parser->encode($XPATH_LCLStorageDate);
					$LCLStorageDate = node_exist(getArrayName($XPATH_LCLStorageDate));

					$items[] = array("LegOrder"=>"1","LegType"=>$LEG_TYPE,"VesselName"=>$TRANSVESSELNAME,"Destination"=>$TRANSDISCHARGE,"Origin"=>$TRANSLOADING,"ETA"=>$TRANS_ETA,"ETD"=>$TRANS_ETD,"BookingStatus"=>$BOOKINGSTATUS,"BookingDesc"=>$BOOKINGDESC,"AddressType"=>$CARRIERTYPE,"CarrierName"=>$CARRIERNAME,"CarrierOrg"=>$CARRIERORG,"LCLAvailability"=>$LCLAvailability,"LCLStorageDate"=>$LCLStorageDate);
				}
			
				 $routing = json_encode($items);

				if ($CONTAINERctr == 1) {
					$CONTAINERctr = 1;
				}
				$OrganizationAddress = jsonPath(
					$universal_shipment, $path_SubUniversalSubShipment.".OrganizationAddress");
				$OrganizationAddress_ctr = $OrganizationAddress;
				if ($OrganizationAddress_ctr != false) {
					$OrganizationAddress = jsonPath($universal_shipment, $path_SubUniversalSubShipment.".OrganizationAddress");
					$OrganizationAddress_ctr = count($OrganizationAddress[0]);
				} else {
					$OrganizationAddress_ctr = 0;
				}
				$OrganizationAddress1 = jsonPath($universal_shipment, $path_AddressUniversalShipment.".OrganizationAddress");
				$OrganizationAddress_ctr1 = $OrganizationAddress1;
				if ($OrganizationAddress_ctr1 != false) {
					$OrganizationAddress1 = jsonPath($universal_shipment, $path_AddressUniversalShipment.".OrganizationAddress");
					$OrganizationAddress_ctr1 = count($OrganizationAddress1[0]);
				} else {
					$OrganizationAddress_ctr1 = 0;
				}

				/*get all organization type*/
				$orgaddress_array = array();  
				for ($a = 0; $a <= $OrganizationAddress_ctr - 1; $a++) {
					$XPATH_ORGANIZATIONCODE = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].OrganizationCode");
					$XPATH_ADDRESS1 = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].Address1");
					$XPATH_ADDRESS2 = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].Address2");
					$XPATH_ADDRESSCODE = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].AddressShortCode");
					$XPATH_COMPANYNAME = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].OrganizationCode");
					$XPATH_PORTNAME = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].Port.Name");
					$XPATH_STATE = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].State");
					$XPATH_POSTCODE = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].Postcode");
					$XPATH_COUNTRY = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].Country.Name");
					$XPATH_ADDRESSTYPE = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].AddressType");
					$XPATH_COMPNAME= jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].CompanyName");

					$PATH_ADDRESSTYPE = $parser->encode($XPATH_ADDRESSTYPE);
					$PATH_ADDRESSTYPE = node_exist(getArrayName($PATH_ADDRESSTYPE));
					
					$XPATH_EMAIL= jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].Email");
					$XPATH_EMAIL_GLOBAL = $parser->encode($XPATH_EMAIL);
					$XPATH_EMAIL_GLOBAL = node_exist(getArrayName($XPATH_EMAIL_GLOBAL));
					$XPATH_ORGCODE_GLOBAL = $parser->encode($XPATH_COMPANYNAME);
					$XPATH_ORGCODE_GLOBAL = node_exist(getArrayName($XPATH_ORGCODE_GLOBAL));

					/*store to json for organization details*/
					$orgaddress_array[] = array("AddressType"=>getArrayName($PATH_ADDRESSTYPE),"Address1"=>node_exist(getArrayName($parser->encode($XPATH_ADDRESS1))),"Address2"=>node_exist(getArrayName($parser->encode($XPATH_ADDRESS2))),"AddressShortCode"=>node_exist(getArrayName($parser->encode($XPATH_ADDRESSCODE))),"CompanyName"=>node_exist(getArrayName($parser->encode($XPATH_COMPNAME))),"Email"=>node_exist(getArrayName($parser->encode($XPATH_EMAIL_GLOBAL))),"OrganizationCode"=>node_exist(getArrayName($parser->encode($XPATH_ORGANIZATIONCODE))));
		    		

					
					
					if ($PATH_ADDRESSTYPE == "ConsigneeDocumentaryAddress") {
						
						$XPATH_COMPANYNAME = $parser->encode($XPATH_COMPANYNAME);
						$PATH_CONSIGNEE = node_exist(getArrayName($XPATH_COMPANYNAME));
						$XPATH_ADDRESS1 = $parser->encode($XPATH_ADDRESS1);
						$PATH_CONSIGNEEADDRESS1 = node_exist(getArrayName($XPATH_ADDRESS1));
						$XPATH_STATE = $parser->encode($XPATH_STATE);
						$PATH_CONSIGNEESTATE = node_exist(getArrayName($XPATH_STATE));
						$XPATH_POSTCODE = $parser->encode($XPATH_POSTCODE);
						$PATH_CONSIGNEEPOSTCODE = node_exist(getArrayName($XPATH_POSTCODE));
						$XPATH_COUNTRY = $parser->encode($XPATH_COUNTRY);
						$PATH_CONSIGNEECOUNTRY = node_exist(getArrayName($XPATH_COUNTRY));
						$CONSIGNEE = $parser->encode($XPATH_ORGANIZATIONCODE);
						$CONSIGNEE = node_exist(getArrayName($CONSIGNEE));

						$CONSIGNEEADDRESS = $PATH_CONSIGNEEADDRESS1 . ", " . $PATH_CONSIGNEESTATE . ", " . $PATH_CONSIGNEEPOSTCODE . ", " . $PATH_CONSIGNEECOUNTRY;
						$CONSIGNORADDRESS = str_replace("'", "", $CONSIGNORADDRESS);
					}

					elseif ($PATH_ADDRESSTYPE == "ConsignorDocumentaryAddress") {
						
						$XPATH_COMPANYNAME = $parser->encode($XPATH_COMPANYNAME);
						$PATH_CONSIGNOR = node_exist(getArrayName($XPATH_COMPANYNAME));
						$XPATH_ADDRESS1 = $parser->encode($XPATH_ADDRESS1);
						$PATH_CONSIGNORADDRESS1 = node_exist(getArrayName($XPATH_ADDRESS1));
						$XPATH_STATE = $parser->encode($XPATH_STATE);
						$PATH_CONSIGNORSTATE = node_exist(getArrayName($XPATH_STATE));
						$XPATH_POSTCODE = $parser->encode($XPATH_POSTCODE);
						$PATH_CONSIGNORPOSTCODE = node_exist(getArrayName($XPATH_POSTCODE));
						$XPATH_COUNTRY = $parser->encode($XPATH_COUNTRY);
						$PATH_CONSIGNORCOUNTRY = node_exist(getArrayName($XPATH_COUNTRY));
						$CONSIGNOR = $parser->encode($XPATH_ORGANIZATIONCODE);
						$CONSIGNOR = node_exist(getArrayName($CONSIGNOR));
						$CONSIGNORADDRESS = $PATH_CONSIGNORADDRESS1 . ", " . $PATH_CONSIGNORSTATE . ", " . $PATH_CONSIGNORPOSTCODE . ", " . $PATH_CONSIGNORCOUNTRY;
						$CONSIGNORADDRESS = str_replace("'", "", $CONSIGNORADDRESS);
						if(strlen($CONSIGNORADDRESS) < 5){
							$CONSIGNORADDRESS="";
						}
					}
				}
				$organization = json_encode($orgaddress_array);

				for ($b = 0; $b <= $OrganizationAddress_ctr1 - 1; $b++) {
						$ORGANIZATIONCODE = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].OrganizationCode");
						$ADDRESS1 = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].Address1");
						$ADDRESS2 = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].Address2");
						$ADDRESSCODE = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].AddressShortCode");
						$COMPANYNAME = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].OrganizationCode");
						$PORTNAME = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].Port.Name");
						$STATE = jsonPath($universal_shipment, $path_AddressUniversalShipment.".OrganizationAddress[$b].State");
						$POSTCODE = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].Postcode");
						$COUNTRY = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].Country.Name");
						$ADDRESSTYPE = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].AddressType");
						$EMAIL = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].Email");
						
						$PATH_ADDRESSTYPE_ = $parser->encode($ADDRESSTYPE);
						$PATH_ADDRESSTYPE_ = node_exist(getArrayName($PATH_ADDRESSTYPE_));
						
						$XPATH_EMAIL_GLOBAL_ = $parser->encode($EMAIL);
						$XPATH_EMAIL_GLOBAL_ = node_exist(getArrayName($XPATH_EMAIL_GLOBAL_));
						$XPATH_ORGCODE_GLOBAL = $parser->encode($COMPANYNAME);
						$XPATH_ORGCODE_GLOBAL = node_exist(getArrayName($XPATH_ORGCODE_GLOBAL));

						if ($PATH_ADDRESSTYPE_ == "SendingForwarderAddress") {
							$XPATH_ORGANIZATIONCODE_ = $parser->encode($ORGANIZATIONCODE);
							$PATH_SENDINGAGENT = node_exist(getArrayName($XPATH_ORGANIZATIONCODE_));
							$XPATH_ADDRESS1 = $parser->encode($ADDRESS1);
							$PATH_SENDINGAGENTADDRESS1 =node_exist(getArrayName($XPATH_ADDRESS1));
							$XPATH_ADDRESS2 = $parser->encode($ADDRESS2);
							$PATH_SENDINGAGENTADDRESS2 = node_exist(getArrayName($XPATH_ADDRESS2));
							$XPATH_ADDRESSCODE = $parser->encode($ADDRESSCODE);
							$PATH_SENDINGAGENTADDRESSCODE = node_exist(getArrayName($XPATH_ADDRESSCODE));
							$XPATH_PORTNAME = $parser->encode($PORTNAME);
							$PATH_SENDINGAGENTPORTNAME = node_exist(getArrayName($XPATH_PORTNAME));
							$XPATH_STATE = $parser->encode($STATE);
							$PATH_SENDINGAGENTSTATE = node_exist(getArrayName($XPATH_STATE));
							$SENDINGAGENTADDRESS = $PATH_SENDINGAGENTADDRESS1 . ", " . $PATH_SENDINGAGENTADDRESSCODE . ", " . $PATH_SENDINGAGENTADDRESS2 . ", " . $PATH_SENDINGAGENTPORTNAME . ", " . $PATH_SENDINGAGENTSTATE;
							
							
						} elseif ($PATH_ADDRESSTYPE_ == "ReceivingForwarderAddress") {
							$XPATH_ORGANIZATIONCODE_ = $parser->encode($ORGANIZATIONCODE);
							$PATH_RECEIVINGAGENT = node_exist(getArrayName($XPATH_ORGANIZATIONCODE_));
							$XPATH_ADDRESS1 = $parser->encode($ADDRESS1);
							$PATH_RECEIVINGAGENTADDRESS1 = node_exist(getArrayName($XPATH_ADDRESS1));
							$XPATH_ADDRESS2 = $parser->encode($ADDRESS2);
							$PATH_RECEIVINGAGENTADDRESS2 = node_exist(getArrayName($XPATH_ADDRESS2));
							$XPATH_ADDRESSCODE = $parser->encode($ADDRESSCODE);
							$PATH_RECEIVINGAGENTADDRESSCODE = node_exist(getArrayName($XPATH_ADDRESSCODE));
							$XPATH_PORTNAME = $parser->encode($PORTNAME);
							$PATH_RECEIVINGAGENTPORTNAME = node_exist(getArrayName($XPATH_PORTNAME));
							$XPATH_STATE = $parser->encode($STATE);
							$PATH_RECEIVINGAGENTSTATE = node_exist(getArrayName($XPATH_STATE));
							$RECEIVINGAGENTADDRESS = $PATH_RECEIVINGAGENTADDRESS1 . ", " . $PATH_RECEIVINGAGENTADDRESSCODE . ", " . $PATH_RECEIVINGAGENTADDRESS2 . ", " . $PATH_RECEIVINGAGENTPORTNAME . ", " . $PATH_RECEIVINGAGENTSTATE;
						}
			
					}
				
            
				if (!empty($SHIPMENTKEY) || $SHIPMENTKEY <> "") {
				$sql = "SELECT * FROM dbo.shipment WHERE dbo.shipment.shipment_num = '$SHIPMENTKEY' AND dbo.shipment.user_id ='$CLIENT_ID'";
				$qryResultShipID = sqlsrv_query($conn, $sql);
				$ifShipIDExist = sqlsrv_has_rows($qryResultShipID);
				$destination_path = "E:/A2BFREIGHT_MANAGER/$client_email/CW_SUCCESS/";
				
					if ($ifShipIDExist == false) {
						if ($TRANS_ETA == '' || $TRANS_ETD == '') {
							 $TRANS_ETA = null;
							 $TRANS_ETD = null;
						}
						
				$sqlInsertRecord = "INSERT INTO shipment
                (user_id ,console_id, shipment_num, master_bill, house_bill, transport_mode,
                vessel_name, voyage_flight_num, vesslloyds, eta, etd, place_delivery, place_receipt,
				consignee, consignor, sending_agent, receiving_agent, receiving_agent_addr, sending_agent_addr, consignee_addr, consignor_addr, trigger_date, container_mode, port_loading, port_discharge,order_number,totalvolume,ata,atd,route_leg,organization,packingline)
                Values(" . $CLIENT_ID . ",'" . $CONSOLNUMBER . "','" . $SHIPMENTKEY . "','" . $WAYBILLNUMBER . "','" . $HOUSEWAYBILLNUMBER . "','" . $TRANSMODE . "','" . $VESSELNAME . "','" . $VOYAGEFLIGHTNO . "','" . $VESSELLOYDSIMO . "','" . $TRANS_ETA . "','" . $TRANS_ETD . "','" . $PLACEOFDELIVERY . "','" . $PLACEOFRECEIPT . "',
				'" . $CONSIGNEE . "','" . $CONSIGNOR . "','" . $PATH_SENDINGAGENT . "','" . $PATH_RECEIVINGAGENT . "','" . $RECEIVINGAGENTADDRESS . "','" . $SENDINGAGENTADDRESS . "','" . $CONSIGNEEADDRESS . "','" . $CONSIGNORADDRESS . "','" . $SHIP_TRIGGERDATE . "','".$CONTAINERMODE."','".$PORTOFLOADING."','".$PORTOFDISCHARGE."','".$ORDER_NUMBER."','".$TOTALVOLUME."','".$ACTUAL_ARRIVAL."','".$ACTUAL_DEPARTURE."','".$routing."','".$organization."','".$pack_line."') SELECT SCOPE_IDENTITY() as id_ship";
						$insertRec = sqlsrv_query($conn, $sqlInsertRecord);
						$sql_getlastshipID = "SELECT  *
						FROM dbo.shipment
						WHERE console_id = '" . $CONSOLNUMBER . "' AND shipment_num = '" . $SHIPMENTKEY . "' AND user_id = ". $CLIENT_ID ."";
						$execRecord_getlastshipID = sqlsrv_query($conn, $sql_getlastshipID);
						while ($row_shipid = sqlsrv_fetch_array($execRecord_getlastshipID, SQLSRV_FETCH_ASSOC)) {
							 $shipcontainer_id = $row_shipid['id'];
						}
				     sqlsrv_next_result($insertRec);
				     sqlsrv_fetch($insertRec);
				     $ship_idlast = sqlsrv_get_field($insertRec, 0);



					for ($a = 0; $a <= $OrganizationAddress_ctr - 1; $a++) {
					$XPATH_COMPANYNAME = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].OrganizationCode");
					$XPATH_ADDRESSTYPE = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].AddressType");
					$XPATH_COMPANY = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].CompanyName");
					$XPATH_EMAIL= jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].Email");
					$PATH_ADDRESSTYPE = $parser->encode($XPATH_ADDRESSTYPE);
					$PATH_ADDRESSTYPE = node_exist(getArrayName($PATH_ADDRESSTYPE));
					$XPATH_EMAIL_GLOBAL = $parser->encode($XPATH_EMAIL);
					$XPATH_EMAIL_GLOBAL = node_exist(getArrayName($XPATH_EMAIL_GLOBAL));
					$XPATH_ORGCODE_GLOBAL = $parser->encode($XPATH_COMPANYNAME);
					$XPATH_ORGCODE_GLOBAL = node_exist(getArrayName($XPATH_ORGCODE_GLOBAL));
					$XPATH_COMPANY = $parser->encode($XPATH_COMPANY);
					$XPATH_COMPANY = node_exist(getArrayName($XPATH_COMPANY));
		
					
					if(is_null($XPATH_EMAIL_GLOBAL) == 'false' || $XPATH_EMAIL_GLOBAL != ''){
					 $sql_contact = "SELECT * FROM dbo.shipment_contacts WHERE dbo.shipment_contacts.shipment_id = ".$shipcontainer_id." AND dbo.shipment_contacts.address_type ='$PATH_ADDRESSTYPE' AND dbo.shipment_contacts.organization_code = '$XPATH_ORGCODE_GLOBAL' AND email='$XPATH_EMAIL_GLOBAL'";
					$qryResultContact = sqlsrv_query($conn, $sql_contact);
					$ifContactExist = sqlsrv_has_rows($qryResultContact);
					
					if($ifContactExist != 1 && $PATH_ADDRESSTYPE == "ConsigneeDocumentaryAddress"){
					   $sql_Insert_contact = "INSERT INTO FROM dbo.shipment_contacts (shipment_id,address_type,organization_code,email,is_default,company_name)
					   VALUES ('".$shipcontainer_id."','".$PATH_ADDRESSTYPE."','".$XPATH_ORGCODE_GLOBAL."','".$XPATH_EMAIL_GLOBAL."','N','".$XPATH_COMPANY."');";
					   $qry_Insert_contact = sqlsrv_query($conn, $sql_Insert_contact);
					}
				}
			}
						
				for ($b = 0; $b <= $OrganizationAddress_ctr1 - 1; $b++) {
					    $XPATH_COMPANYNAME = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].OrganizationCode");
						$ADDRESSTYPE = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].AddressType");
						$EMAIL = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].Email");
						$XPATH_EMAIL_GLOBAL_ = $parser->encode($EMAIL);
						$XPATH_EMAIL_GLOBAL_ = node_exist(getArrayName($XPATH_EMAIL_GLOBAL_));
						$XPATH_ORGCODE_GLOBAL = $parser->encode($COMPANYNAME);
						$XPATH_ORGCODE_GLOBAL = node_exist(getArrayName($XPATH_ORGCODE_GLOBAL));
						$PATH_ADDRESSTYPE_ = $parser->encode($ADDRESSTYPE);
						$PATH_ADDRESSTYPE_ = node_exist(getArrayName($PATH_ADDRESSTYPE_));
						
						
						$XPATH_COMPANY_ = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].CompanyName");
						$XPATH_COMPANY_ = $parser->encode($XPATH_COMPANY_);
						$XPATH_COMPANY_ = node_exist(getArrayName($XPATH_COMPANY_));
					
					
					if(is_null($XPATH_EMAIL_GLOBAL_) == 'false' || $XPATH_EMAIL_GLOBAL_ != ''){
					 $sql_contact = "SELECT * FROM dbo.shipment_contacts WHERE dbo.shipment_contacts.shipment_id = '".$shipcontainer_id."' AND dbo.shipment_contacts.address_type ='$PATH_ADDRESSTYPE_' AND dbo.shipment_contacts.organization_code = '$XPATH_ORGCODE_GLOBAL' AND email='$XPATH_EMAIL_GLOBAL_'";
					$qryResultContact = sqlsrv_query($conn, $sql_contact);
					$ifContactExist = sqlsrv_has_rows($qryResultContact);
					
					if($ifContactExist != 1 && $PATH_ADDRESSTYPE_ == "ConsigneeDocumentaryAddress"){
					   $sql_Insert_contact = "INSERT INTO FROM dbo.shipment_contacts (shipment_id,address_type,organization_code,email,is_default,company_name)
					   VALUES ('".$shipcontainer_id."','".$PATH_ADDRESSTYPE_."','".$XPATH_ORGCODE_GLOBAL."','".$XPATH_EMAIL_GLOBAL_."','N','".$XPATH_COMPANY_."');";
					   $qry_Insert_contact = sqlsrv_query($conn, $sql_Insert_contact);
					}	
				}
							
			}
						
						

						if ($CONTAINERctr > 1) {
							for ($c = 0; $c <= $CONTAINERctr - 1; $c++) {
								$XPATH_CONTAINERNUMBER = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ContainerNumber");
								$CONTAINERNUMBER = $parser->encode($XPATH_CONTAINERNUMBER);
								$CONTAINERNUMBER = node_exist(getArrayName($CONTAINERNUMBER));
								
								$XPATH_CONTAINERTYPE = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ContainerType.Code");
								$CONTAINERTYPE = $parser->encode($XPATH_CONTAINERTYPE);
								$CONTAINERTYPE = node_exist(getArrayName($CONTAINERTYPE));
								
								$XPATH_DELIVERYMODE = jsonPath($universal_shipment, $path_ContainerCollection."[$c].DeliveryMode");
								$DELIVERYMODE = $parser->encode($XPATH_DELIVERYMODE);
								$DELIVERYMODE = node_exist(getArrayName($DELIVERYMODE));
								
								$XPATH_FCLUNLOADFROMVESSEL = jsonPath($universal_shipment, $path_ContainerCollection."[$c].FCLUnloadFromVessel");
								$FCLUNLOADFROMVESSEL = $parser->encode($XPATH_FCLUNLOADFROMVESSEL);
								$FCLUNLOADFROMVESSEL = node_exist(getArrayName($FCLUNLOADFROMVESSEL));
								
								$XPATH_ARRIVALCARTAGEADVISED = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ArrivalCartageAdvised");
								$ARRIVALCARTAGEADVISED = $parser->encode($XPATH_ARRIVALCARTAGEADVISED);
								$ARRIVALCARTAGEADVISED = node_exist(getArrayName($ARRIVALCARTAGEADVISED));
								
								$XPATH_SLOTDATE = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ArrivalSlotDateTime");
								$SLOTDATE = $parser->encode($XPATH_SLOTDATE);
								$SLOTDATE = node_exist(getArrayName($SLOTDATE));
								
								$XPATH_WHARFOUT = jsonPath($universal_shipment, $path_ContainerCollection."[$c].FCLWharfGateOut");
								$WHARFOUT = $parser->encode($XPATH_WHARFOUT);
								$WHARFOUT = node_exist(getArrayName($WHARFOUT));
								
								$XPATH_ESTFULLDELIVER = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ArrivalEstimatedDelivery");
								$ESTFULLDELIVER = $parser->encode($XPATH_ESTFULLDELIVER);
								$ESTFULLDELIVER = node_exist(getArrayName($ESTFULLDELIVER));
								
								$XPATH_ACTFULLDELIVER = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ArrivalCartageComplete");
								$ACTFULLDELIVER = $parser->encode($XPATH_ACTFULLDELIVER);
								$ACTFULLDELIVER = node_exist(getArrayName($ACTFULLDELIVER));
								
								$XPATH_EMPRETURNEDBY = jsonPath($universal_shipment, $path_ContainerCollection."[$c].EmptyReturnedBy");
								$EMPRETURNEDBY = $parser->encode($XPATH_EMPRETURNEDBY);
								$EMPRETURNEDBY = node_exist(getArrayName($EMPRETURNEDBY));
								
								$XPATH_EMPFORRETURNED = jsonPath($universal_shipment, $path_ContainerCollection."[$c].EmptyReadyForReturn");
								$EMPFORRETURNED = $parser->encode($XPATH_EMPFORRETURNED);
								$EMPFORRETURNED = node_exist(getArrayName($EMPFORRETURNED));
								
								$XPATH_CUSTOMSREF = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ImportDepotCustomsReference");
								$CUSTOMSREF = $parser->encode($XPATH_CUSTOMSREF);
								$CUSTOMSREF = node_exist(getArrayName($CUSTOMSREF));
								
								$XPATH_PORTTRANSREF = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ArrivalCartageRef");
								$PORTTRANSREF = $parser->encode($XPATH_PORTTRANSREF);
								$PORTTRANSREF = node_exist(getArrayName($PORTTRANSREF));
								
								$XPATH_SLOTTRANSREF = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ArrivalSlotReference");
								$SLOTTRANSREF = $parser->encode($XPATH_SLOTTRANSREF);
								$SLOTTRANSREF = node_exist(getArrayName($SLOTTRANSREF));
								
								$XPATH_DORELEASE = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ContainerImportDORelease");
								$DORELEASE = $parser->encode($XPATH_DORELEASE);
								$DORELEASE = node_exist(getArrayName($DORELEASE));
						
								$XPATH_CATEGORYDESCRIPTION = jsonPath($universal_shipment,"$.Body.UniversalShipment.Shipment.ContainerCollection.Container[$c].ContainerType.Category.Description");
								$CATEGORYDESCRIPTION = $parser->encode($XPATH_CATEGORYDESCRIPTION);
								$CATEGORYDESCRIPTION = node_exist(getArrayName($CATEGORYDESCRIPTION));
								
								$DeliveryCartageAdvised = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryCartageAdvised");
								$DeliveryCartageAdvised = $parser->encode($DeliveryCartageAdvised);
								$DeliveryCartageAdvised = node_exist(getArrayName($DeliveryCartageAdvised));
								
								$DeliveryCartageCompleted = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryCartageCompleted");
								$DeliveryCartageCompleted = $parser->encode($DeliveryCartageCompleted);
								$DeliveryCartageCompleted = node_exist(getArrayName($DeliveryCartageCompleted));
								
								$DeliveryRequiredFrom = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryRequiredFrom");
								$DeliveryRequiredFrom = $parser->encode($DeliveryRequiredFrom);
								$DeliveryRequiredFrom = node_exist(getArrayName($DeliveryRequiredFrom));
								
								$DeliveryRequiredBy = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryRequiredBy");
								$DeliveryRequiredBy = $parser->encode($DeliveryRequiredBy);
								$DeliveryRequiredBy = node_exist(getArrayName($DeliveryRequiredBy));
								
								$EstimatedDelivery = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.EstimatedDelivery");
								$EstimatedDelivery = $parser->encode($EstimatedDelivery);
								$EstimatedDelivery = node_exist(getArrayName($EstimatedDelivery));
								
								$DeliveryLabourTime = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryLabourTime");
								$DeliveryLabourTime = $parser->encode($DeliveryLabourTime);
								$DeliveryLabourTime = node_exist(getArrayName($DeliveryLabourTime));
								
								$DeliveryTruckWaitTime = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryTruckWaitTime");
								$DeliveryTruckWaitTime = $parser->encode($DeliveryTruckWaitTime);
								$DeliveryTruckWaitTime = node_exist(getArrayName($DeliveryTruckWaitTime));
								
								$XPATH_TOTALHEIGHT = jsonPath($universal_shipment, $path_ContainerCollection."[$c].TotalHeight");
								$TOTALHEIGHT = $parser->encode($XPATH_TOTALHEIGHT);
								$TOTALHEIGHT = node_exist(getArrayName($TOTALHEIGHT));

								$XPATH_TOTALLENGTH = jsonPath($universal_shipment, $path_ContainerCollection."[$c].TotalLength");
								$TOTALLENGTH = $parser->encode($XPATH_TOTALLENGTH);
								$TOTALLENGTH = node_exist(getArrayName($TOTALLENGTH));

								$XPATH_TOTALWIDTH = jsonPath($universal_shipment, $path_ContainerCollection."[$c].TotalWidth");
								$TOTALWIDTH = $parser->encode($XPATH_TOTALWIDTH);
								$TOTALWIDTH = node_exist(getArrayName($TOTALWIDTH));
								
								$sqlInsertRecord_Container = "INSERT INTO shipment_container
								(shipment_id,containershipnumber, containernumber, containertype, containerdeliverymode, containerdescription,fcl_unload,port_transport_booked,slot_date,wharf_gate_out,estimated_full_delivery,actual_full_deliver,empty_returned_by,empty_readyfor_returned,customs_Ref,port_transport_ref,slot_book_ref,do_release,trans_book_req,trans_actual_deliver,trans_deliverreq_from,trans_deliverreq_by,trans_estimated_delivery,trans_delivery_labour,trans_wait_time,length,width,height)
								Values(".$shipcontainer_id.",'" . $SHIPMENTKEY . "','" . $CONTAINERNUMBER . "','" . $CONTAINERTYPE . "','" . $DELIVERYMODE . "','" . $CATEGORYDESCRIPTION . "','".$FCLUNLOADFROMVESSEL."','".$ARRIVALCARTAGEADVISED."','".$SLOTDATE."','".$WHARFOUT."','".$ESTFULLDELIVER."','".$ACTFULLDELIVER."','".$EMPRETURNEDBY."','".$EMPFORRETURNED."','".$CUSTOMSREF."','".$PORTTRANSREF."','".$SLOTTRANSREF."','".$DORELEASE."','".$DeliveryCartageAdvised."','".$DeliveryCartageCompleted."','".$DeliveryRequiredFrom."','".$DeliveryRequiredBy."','".$EstimatedDelivery."','".$DeliveryLabourTime."','".$DeliveryTruckWaitTime."','".$TOTALLENGTH."','".$TOTALWIDTH."','".$TOTALHEIGHT."')";
								$insertRecContainer = sqlsrv_query($conn, $sqlInsertRecord_Container);

							}
						} elseif ($CONTAINERctr == 1) {
							for ($c = 1; $c <= $CONTAINERctr; $c++) {
								$XPATH_CONTAINERNUMBER = jsonPath($universal_shipment, $path_ContainerCollection.".ContainerNumber");
								$CONTAINERNUMBER = $parser->encode($XPATH_CONTAINERNUMBER);
								$CONTAINERNUMBER = node_exist(getArrayName($CONTAINERNUMBER));
								
								$XPATH_CONTAINERTYPE = jsonPath($universal_shipment, $path_ContainerCollection.".ContainerType.Code");
								$CONTAINERTYPE = $parser->encode($XPATH_CONTAINERTYPE);
								$CONTAINERTYPE = node_exist(getArrayName($CONTAINERTYPE));
								
								$XPATH_DELIVERYMODE = jsonPath($universal_shipment, $path_ContainerCollection.".DeliveryMode");
								$DELIVERYMODE = $parser->encode($XPATH_DELIVERYMODE);
								$DELIVERYMODE = node_exist(getArrayName($DELIVERYMODE));
								
								$XPATH_CATEGORYDESCRIPTION = jsonPath($universal_shipment,$path_ContainerCollection.".ContainerType.Category.Description");
								$CATEGORYDESCRIPTION = $parser->encode($XPATH_CATEGORYDESCRIPTION);
								$CATEGORYDESCRIPTION = node_exist(getArrayName($CATEGORYDESCRIPTION));
								
								$XPATH_FCLUNLOADFROMVESSEL = jsonPath($universal_shipment, $path_ContainerCollection.".FCLUnloadFromVessel");
								$FCLUNLOADFROMVESSEL = $parser->encode($XPATH_FCLUNLOADFROMVESSEL);
								$FCLUNLOADFROMVESSEL = node_exist(getArrayName($FCLUNLOADFROMVESSEL));
								
								$XPATH_ARRIVALCARTAGEADVISED = jsonPath($universal_shipment, $path_ContainerCollection.".ArrivalCartageAdvised");
								$ARRIVALCARTAGEADVISED = $parser->encode($XPATH_ARRIVALCARTAGEADVISED);
								$ARRIVALCARTAGEADVISED = node_exist(getArrayName($ARRIVALCARTAGEADVISED));
								
								$XPATH_SLOTDATE = jsonPath($universal_shipment, $path_ContainerCollection.".ArrivalSlotDateTime");
								$SLOTDATE = $parser->encode($XPATH_SLOTDATE);
								$SLOTDATE = node_exist(getArrayName($SLOTDATE));
								
								$XPATH_WHARFOUT = jsonPath($universal_shipment, $path_ContainerCollection.".FCLWharfGateOut");
								$WHARFOUT = $parser->encode($XPATH_WHARFOUT);
								$WHARFOUT = node_exist(getArrayName($WHARFOUT));
								
								$XPATH_ESTFULLDELIVER = jsonPath($universal_shipment, $path_ContainerCollection.".ArrivalEstimatedDelivery");
								$ESTFULLDELIVER = $parser->encode($XPATH_ESTFULLDELIVER);
								$ESTFULLDELIVER = node_exist(getArrayName($ESTFULLDELIVER));
								
								$XPATH_ACTFULLDELIVER = jsonPath($universal_shipment, $path_ContainerCollection.".ArrivalCartageComplete");
								$ACTFULLDELIVER = $parser->encode($XPATH_ACTFULLDELIVER);
								$ACTFULLDELIVER = node_exist(getArrayName($ACTFULLDELIVER));
								
								$XPATH_EMPRETURNEDBY = jsonPath($universal_shipment, $path_ContainerCollection.".EmptyReturnedBy");
								$EMPRETURNEDBY = $parser->encode($XPATH_EMPRETURNEDBY);
								$EMPRETURNEDBY = node_exist(getArrayName($EMPRETURNEDBY));
								
								$XPATH_EMPFORRETURNED = jsonPath($universal_shipment, $path_ContainerCollection.".EmptyReadyForReturn");
								$EMPFORRETURNED = $parser->encode($XPATH_EMPFORRETURNED);
								$EMPFORRETURNED = node_exist(getArrayName($EMPFORRETURNED));
								
								$XPATH_CUSTOMSREF = jsonPath($universal_shipment, $path_ContainerCollection.".ImportDepotCustomsReference");
								$CUSTOMSREF = $parser->encode($XPATH_CUSTOMSREF);
								$CUSTOMSREF = node_exist(getArrayName($CUSTOMSREF));
								
								$XPATH_PORTTRANSREF = jsonPath($universal_shipment, $path_ContainerCollection.".ArrivalCartageRef");
								$PORTTRANSREF = $parser->encode($XPATH_PORTTRANSREF);
								$PORTTRANSREF = node_exist(getArrayName($PORTTRANSREF));
								
								$XPATH_SLOTTRANSREF = jsonPath($universal_shipment, $path_ContainerCollection.".ArrivalSlotReference");
								$SLOTTRANSREF = $parser->encode($XPATH_SLOTTRANSREF);
								$SLOTTRANSREF = node_exist(getArrayName($SLOTTRANSREF));
								
								$XPATH_DORELEASE = jsonPath($universal_shipment, $path_ContainerCollection.".ContainerImportDORelease");
								$DORELEASE = $parser->encode($XPATH_DORELEASE);
								$DORELEASE = node_exist(getArrayName($DORELEASE));
								
								$DeliveryCartageAdvised = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryCartageAdvised");
								$DeliveryCartageAdvised = $parser->encode($DeliveryCartageAdvised);
								$DeliveryCartageAdvised = node_exist(getArrayName($DeliveryCartageAdvised));
								
								$DeliveryCartageCompleted = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryCartageCompleted");
								$DeliveryCartageCompleted = $parser->encode($DeliveryCartageCompleted);
								$DeliveryCartageCompleted = node_exist(getArrayName($DeliveryCartageCompleted));
								
								$DeliveryRequiredFrom = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryRequiredFrom");
								$DeliveryRequiredFrom = $parser->encode($DeliveryRequiredFrom);
								$DeliveryRequiredFrom = node_exist(getArrayName($DeliveryRequiredFrom));
								
								$DeliveryRequiredBy = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryRequiredBy");
								$DeliveryRequiredBy = $parser->encode($DeliveryRequiredBy);
								$DeliveryRequiredBy = node_exist(getArrayName($DeliveryRequiredBy));
								
								$EstimatedDelivery = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.EstimatedDelivery");
								$EstimatedDelivery = $parser->encode($EstimatedDelivery);
								$EstimatedDelivery = node_exist(getArrayName($EstimatedDelivery));
								
								$DeliveryLabourTime = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryLabourTime");
								$DeliveryLabourTime = $parser->encode($DeliveryLabourTime);
								$DeliveryLabourTime = node_exist(getArrayName($DeliveryLabourTime));
								
								$DeliveryTruckWaitTime = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryTruckWaitTime");
								$DeliveryTruckWaitTime = $parser->encode($DeliveryTruckWaitTime);
								$DeliveryTruckWaitTime = node_exist(getArrayName($DeliveryTruckWaitTime));

								$XPATH_TOTALHEIGHT = jsonPath($universal_shipment, $path_ContainerCollection.".TotalHeight");
								$TOTALHEIGHT = $parser->encode($XPATH_TOTALHEIGHT);
								$TOTALHEIGHT = node_exist(getArrayName($TOTALHEIGHT));

								$XPATH_TOTALLENGTH = jsonPath($universal_shipment, $path_ContainerCollection.".TotalLength");
								$TOTALLENGTH = $parser->encode($XPATH_TOTALLENGTH);
								$TOTALLENGTH = node_exist(getArrayName($TOTALLENGTH));

								$XPATH_TOTALWIDTH = jsonPath($universal_shipment, $path_ContainerCollection.".TotalWidth");
								$TOTALWIDTH = $parser->encode($XPATH_TOTALWIDTH);
								$TOTALWIDTH = node_exist(getArrayName($TOTALWIDTH));
								
								$sqlInsertRecord_Container = "INSERT INTO shipment_container
								(shipment_id,containershipnumber, containernumber, containertype, containerdeliverymode, containerdescription,fcl_unload,port_transport_booked,slot_date,wharf_gate_out,estimated_full_delivery,actual_full_deliver,empty_returned_by,empty_readyfor_returned,customs_Ref,port_transport_ref,slot_book_ref,do_release,trans_book_req,trans_actual_deliver,trans_deliverreq_from,trans_deliverreq_by,trans_estimated_delivery,trans_delivery_labour,trans_wait_time,length,width,height)
								Values(".$shipcontainer_id.",'" . $SHIPMENTKEY . "','" . $CONTAINERNUMBER . "','" . $CONTAINERTYPE . "','" . $DELIVERYMODE . "','" . $CATEGORYDESCRIPTION . "','".$FCLUNLOADFROMVESSEL."','".$ARRIVALCARTAGEADVISED."','".$SLOTDATE."','".$WHARFOUT."','".$ESTFULLDELIVER."','".$ACTFULLDELIVER."','".$EMPRETURNEDBY."','".$EMPFORRETURNED."','".$CUSTOMSREF."','".$PORTTRANSREF."','".$SLOTTRANSREF."','".$DORELEASE."','".$DeliveryCartageAdvised."','".$DeliveryCartageCompleted."','".$DeliveryRequiredFrom."','".$DeliveryRequiredBy."','".$EstimatedDelivery."','".$DeliveryLabourTime."','".$DeliveryTruckWaitTime."','".$TOTALLENGTH."','".$TOTALWIDTH."','".$TOTALHEIGHT."')";
								$insertRecContainer = sqlsrv_query($conn, $sqlInsertRecord_Container);
							}
						}

				
						$destination_path = "E:/A2BFREIGHT_MANAGER/$client_email/CW_SUCCESS/";						
						process_shipment($SHIPMENTKEY,$client_email,$ship_idlast,$webservicelink,$service_user,$service_password,$server_id,$enterprise_id,$auth,$company_code);
						if(!file_exists($destination_path.$filename)){
						rename($filename, $destination_path . pathinfo($filename, PATHINFO_BASENAME));
						file_log($SHIPMENTKEY,$filename,$CLIENT_ID);
						}
		
					} else 
			{
				if ($TRANS_ETA == '' || $TRANS_ETD == '') {
							 $TRANS_ETA = null;
							 $TRANS_ETD = null;
						}
						while ($row_shipid = sqlsrv_fetch_array($qryResultShipID, SQLSRV_FETCH_ASSOC)) {
								 $ship_idlast = $row_shipid['id'];
						}
						$sqlUpdateRecord = "Update shipment
				        Set console_id='$CONSOLNUMBER', master_bill ='$WAYBILLNUMBER', house_bill='$HOUSEWAYBILLNUMBER', transport_mode='$TRANSMODE',
				        vessel_name='$VESSELNAME', voyage_flight_num='$VOYAGEFLIGHTNO', vesslloyds='$VESSELLOYDSIMO', eta='$TRANS_ETA', etd='$TRANS_ETD', place_delivery='$PLACEOFDELIVERY', place_receipt='$PLACEOFRECEIPT',
				        consignee='$CONSIGNEE',consignor='$CONSIGNOR',sending_agent='$PATH_SENDINGAGENT',receiving_agent='$PATH_RECEIVINGAGENT',receiving_agent_addr='$RECEIVINGAGENTADDRESS',
				        sending_agent_addr='$SENDINGAGENTADDRESS',consignee_addr='$CONSIGNEEADDRESS',consignor_addr='$CONSIGNORADDRESS',trigger_date='$SHIP_TRIGGERDATE', container_mode='$CONTAINERMODE', port_loading='$PORTOFLOADING', port_discharge='$PORTOFDISCHARGE', order_number='$ORDER_NUMBER', totalvolume='$TOTALVOLUME', ata='$ACTUAL_ARRIVAL', atd='$ACTUAL_DEPARTURE', route_leg='$routing', organization='$organization',packingline='$pack_line'
				        WHERE shipment_num = '$SHIPMENTKEY' AND user_id = '$CLIENT_ID'";
						$updateRec = sqlsrv_query($conn, $sqlUpdateRecord);
	
						//rename($filename, $destination_path . pathinfo($filename, PATHINFO_BASENAME));
                        //UPDATE RECORD FOR NEW CONTAINER
						
				for ($a = 0; $a <= $OrganizationAddress_ctr - 1; $a++) {
					$XPATH_COMPANYNAME_ = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].OrganizationCode");
					$XPATH_ADDRESSTYPE = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].AddressType");
					$XPATH_EMAIL= jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].Email");
					$PATH_ADDRESSTYPE = $parser->encode($XPATH_ADDRESSTYPE);
					$PATH_ADDRESSTYPE = node_exist(getArrayName($PATH_ADDRESSTYPE));
					
					$XPATH_EMAIL_GLOBAL = $parser->encode($XPATH_EMAIL);
					$XPATH_EMAIL_GLOBAL = node_exist(getArrayName($XPATH_EMAIL_GLOBAL));
					
					$XPATH_ORGCODE_GLOBAL_ = $parser->encode($XPATH_COMPANYNAME_);
					$XPATH_ORGCODE_GLOBAL_ = node_exist(getArrayName($XPATH_ORGCODE_GLOBAL_));
					
					$XPATH_COMPANY = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].CompanyName");
					$XPATH_COMPANY = $parser->encode($XPATH_COMPANY);
					$XPATH_COMPANY = node_exist(getArrayName($XPATH_COMPANY));
					
				
				if(is_null($XPATH_EMAIL_GLOBAL) == 'false' || $XPATH_EMAIL_GLOBAL != ''){
					 $sql_contact_ = "SELECT * FROM dbo.shipment_contacts WHERE dbo.shipment_contacts.shipment_id = ".$ship_idlast." AND dbo.shipment_contacts.address_type ='$PATH_ADDRESSTYPE' AND dbo.shipment_contacts.organization_code = '$XPATH_ORGCODE_GLOBAL_' AND email='$XPATH_EMAIL_GLOBAL'";
					$qryResultContact = sqlsrv_query($conn, $sql_contact_);
					$ifContactExist = sqlsrv_has_rows($qryResultContact);
					$row_count = sqlsrv_num_rows($qryResultContact);
					
					if($ifContactExist != 1 && $PATH_ADDRESSTYPE == "ConsigneeDocumentaryAddress"){
					   $sql_Insert_contact_ = "INSERT INTO dbo.shipment_contacts (shipment_id,address_type,organization_code,email,is_default,company_name)
					   VALUES (".$ship_idlast.",'".$PATH_ADDRESSTYPE."','".$XPATH_ORGCODE_GLOBAL_."','".$XPATH_EMAIL_GLOBAL."','N','".$XPATH_COMPANY."');";
					   $sql_Insert_contact_ = sqlsrv_query($conn, $sql_Insert_contact_);
					}
				}
			}
						
				for ($b = 0; $b <= $OrganizationAddress_ctr1 - 1; $b++) {
						$XPATH_COMPANYNAME_ = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].OrganizationCode");
						$ADDRESSTYPE_ = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].AddressType");
						$EMAIL_ = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].Email");
						$XPATH_EMAIL_GLOBAL_ = $parser->encode($EMAIL_);
						$XPATH_EMAIL_GLOBAL_ = node_exist(getArrayName($XPATH_EMAIL_GLOBAL_));
						
						$XPATH_ORGCODE_GLOBAL_ = $parser->encode($XPATH_COMPANYNAME_);
						$XPATH_ORGCODE_GLOBAL_ = node_exist(getArrayName($XPATH_ORGCODE_GLOBAL_));
						
						$PATH_ADDRESSTYPE_ = $parser->encode($ADDRESSTYPE_);
					    $PATH_ADDRESSTYPE_ = node_exist(getArrayName($PATH_ADDRESSTYPE_));
						
						$XPATH_COMPANY_ = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].CompanyName");
						$XPATH_COMPANY_ = $parser->encode($XPATH_COMPANY_);
						$XPATH_COMPANY_ = node_exist(getArrayName($XPATH_COMPANY_));
						
					
					if(is_null($XPATH_EMAIL_GLOBAL_) == 'false' || $XPATH_EMAIL_GLOBAL_ != ''){
					 $sql_contact = "SELECT * FROM dbo.shipment_contacts WHERE dbo.shipment_contacts.shipment_id = ".$ship_idlast." AND dbo.shipment_contacts.address_type ='$PATH_ADDRESSTYPE_' AND dbo.shipment_contacts.organization_code = '$XPATH_ORGCODE_GLOBAL_' AND email='$XPATH_EMAIL_GLOBAL_'";
					$qryResultContact = sqlsrv_query($conn, $sql_contact);
					$ifContactExist = sqlsrv_has_rows($qryResultContact);

				if($ifContactExist != 1 && $PATH_ADDRESSTYPE_ == "ConsigneeDocumentaryAddress"){
					   $sql_Insert_contact = "INSERT INTO dbo.shipment_contacts (shipment_id,address_type,organization_code,email,is_default,company_name)
					   VALUES (".$ship_idlast.",'".$PATH_ADDRESSTYPE."','".$XPATH_ORGCODE_GLOBAL_."','".$XPATH_EMAIL_GLOBAL_."','N','".$XPATH_COMPANY_."');";
					   $qry_Insert_contact = sqlsrv_query($conn, $sql_Insert_contact);
					}	
				}
			}
		
						if ($CONTAINERctr > 1) {
							for ($c = 0; $c <= $CONTAINERctr - 1; $c++) {
								$XPATH_CONTAINERNUMBER = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ContainerNumber");
								$CONTAINERNUMBER = $parser->encode($XPATH_CONTAINERNUMBER);
								$CONTAINERNUMBER = node_exist(getArrayName($CONTAINERNUMBER));
								
								$XPATH_CONTAINERTYPE = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ContainerType.Code");
								$CONTAINERTYPE = $parser->encode($XPATH_CONTAINERTYPE);
								$CONTAINERTYPE = node_exist(getArrayName($CONTAINERTYPE));
								
								$XPATH_DELIVERYMODE = jsonPath($universal_shipment, $path_ContainerCollection."[$c].DeliveryMode");
								$DELIVERYMODE = $parser->encode($XPATH_DELIVERYMODE);
								$DELIVERYMODE = node_exist(getArrayName($DELIVERYMODE));
								
								$XPATH_CATEGORYDESCRIPTION = jsonPath($universal_shipment,$path_ContainerCollection."[$c].ContainerType.Category.Description");
								$CATEGORYDESCRIPTION = $parser->encode($XPATH_CATEGORYDESCRIPTION);
								$CATEGORYDESCRIPTION = node_exist(getArrayName($CATEGORYDESCRIPTION));
								
								$XPATH_FCLUNLOADFROMVESSEL = jsonPath($universal_shipment, $path_ContainerCollection."[$c].FCLUnloadFromVessel");
								$FCLUNLOADFROMVESSEL = $parser->encode($XPATH_FCLUNLOADFROMVESSEL);
								$FCLUNLOADFROMVESSEL = node_exist(getArrayName($FCLUNLOADFROMVESSEL));
								
								$XPATH_ARRIVALCARTAGEADVISED = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ArrivalCartageAdvised");
								$ARRIVALCARTAGEADVISED = $parser->encode($XPATH_ARRIVALCARTAGEADVISED);
								$ARRIVALCARTAGEADVISED = node_exist(getArrayName($ARRIVALCARTAGEADVISED));
								
								$XPATH_SLOTDATE = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ArrivalSlotDateTime");
								$SLOTDATE = $parser->encode($XPATH_SLOTDATE);
								$SLOTDATE = node_exist(getArrayName($SLOTDATE));
								
								$XPATH_WHARFOUT = jsonPath($universal_shipment, $path_ContainerCollection."[$c].FCLWharfGateOut");
								$WHARFOUT = $parser->encode($XPATH_WHARFOUT);
								$WHARFOUT = node_exist(getArrayName($WHARFOUT));
								
								$XPATH_ESTFULLDELIVER = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ArrivalEstimatedDelivery");
								$ESTFULLDELIVER = $parser->encode($XPATH_ESTFULLDELIVER);
								$ESTFULLDELIVER = node_exist(getArrayName($ESTFULLDELIVER));
								
								$XPATH_ACTFULLDELIVER = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ArrivalCartageComplete");
								$ACTFULLDELIVER = $parser->encode($XPATH_ACTFULLDELIVER);
								$ACTFULLDELIVER = node_exist(getArrayName($ACTFULLDELIVER));
								
								$XPATH_EMPRETURNEDBY = jsonPath($universal_shipment, $path_ContainerCollection."[$c].EmptyReturnedBy");
								$EMPRETURNEDBY = $parser->encode($XPATH_EMPRETURNEDBY);
								$EMPRETURNEDBY = node_exist(getArrayName($EMPRETURNEDBY));
								
								$XPATH_EMPFORRETURNED = jsonPath($universal_shipment, $path_ContainerCollection."[$c].EmptyReadyForReturn");
								$EMPFORRETURNED = $parser->encode($XPATH_EMPFORRETURNED);
								$EMPFORRETURNED = node_exist(getArrayName($EMPFORRETURNED));
								
								$XPATH_CUSTOMSREF = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ImportDepotCustomsReference");
								$CUSTOMSREF = $parser->encode($XPATH_CUSTOMSREF);
								$CUSTOMSREF = node_exist(getArrayName($CUSTOMSREF));
								
								$XPATH_PORTTRANSREF = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ArrivalCartageRef");
								$PORTTRANSREF = $parser->encode($XPATH_PORTTRANSREF);
								$PORTTRANSREF = node_exist(getArrayName($PORTTRANSREF));
								
								$XPATH_SLOTTRANSREF = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ArrivalSlotReference");
								$SLOTTRANSREF = $parser->encode($XPATH_SLOTTRANSREF);
								$SLOTTRANSREF = node_exist(getArrayName($SLOTTRANSREF));
								
								$XPATH_DORELEASE = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ContainerImportDORelease");
								$DORELEASE = $parser->encode($XPATH_DORELEASE);
								$DORELEASE = node_exist(getArrayName($DORELEASE));

								$XPATH_TOTALHEIGHT = jsonPath($universal_shipment, $path_ContainerCollection."[$c].TotalHeight");
								$TOTALHEIGHT = $parser->encode($XPATH_TOTALHEIGHT);
								$TOTALHEIGHT = node_exist(getArrayName($TOTALHEIGHT));

								$XPATH_TOTALLENGTH = jsonPath($universal_shipment, $path_ContainerCollection."[$c].TotalLength");
								$TOTALLENGTH = $parser->encode($XPATH_TOTALLENGTH);
								$TOTALLENGTH = node_exist(getArrayName($TOTALLENGTH));

								$XPATH_TOTALWIDTH = jsonPath($universal_shipment, $path_ContainerCollection."[$c].TotalWidth");
								$TOTALWIDTH = $parser->encode($XPATH_TOTALWIDTH);
								$TOTALWIDTH = node_exist(getArrayName($TOTALWIDTH));
								
								$DeliveryCartageAdvised = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryCartageAdvised");
								$DeliveryCartageAdvised = $parser->encode($DeliveryCartageAdvised);
								$DeliveryCartageAdvised = node_exist(getArrayName($DeliveryCartageAdvised));
								
								$DeliveryCartageCompleted = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryCartageCompleted");
								$DeliveryCartageCompleted = $parser->encode($DeliveryCartageCompleted);
								$DeliveryCartageCompleted = node_exist(getArrayName($DeliveryCartageCompleted));
								
								$DeliveryRequiredFrom = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryRequiredFrom");
								$DeliveryRequiredFrom = $parser->encode($DeliveryRequiredFrom);
								$DeliveryRequiredFrom = node_exist(getArrayName($DeliveryRequiredFrom));
								
								$DeliveryRequiredBy = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryRequiredBy");
								$DeliveryRequiredBy = $parser->encode($DeliveryRequiredBy);
								$DeliveryRequiredBy = node_exist(getArrayName($DeliveryRequiredBy));
								
								$EstimatedDelivery = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.EstimatedDelivery");
								$EstimatedDelivery = $parser->encode($EstimatedDelivery);
								$EstimatedDelivery = node_exist(getArrayName($EstimatedDelivery));
								
								$DeliveryLabourTime = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryLabourTime");
								$DeliveryLabourTime = $parser->encode($DeliveryLabourTime);
								$DeliveryLabourTime = node_exist(getArrayName($DeliveryLabourTime));
								
								$DeliveryTruckWaitTime = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryTruckWaitTime");
								$DeliveryTruckWaitTime = $parser->encode($DeliveryTruckWaitTime);
								$DeliveryTruckWaitTime = node_exist(getArrayName($DeliveryTruckWaitTime));
								
								
								$sqlSearchContainer = "Select * from shipment_container WHERE shipment_id=".$ship_idlast." AND containernumber='$CONTAINERNUMBER'";
								$searchContainer = sqlsrv_query($conn, $sqlSearchContainer);
								$ifContainerIDExist = sqlsrv_has_rows($searchContainer);
								
								if($ifContainerIDExist == 'true')
							    {
								$sqlUpdateRecord_Container = "Update shipment_container SET
								containernumber='$CONTAINERNUMBER', containertype='$CONTAINERTYPE', containerdeliverymode='$DELIVERYMODE', containerdescription='$CATEGORYDESCRIPTION', fcl_unload='$FCLUNLOADFROMVESSEL', port_transport_booked='$ARRIVALCARTAGEADVISED', slot_date='$SLOTDATE', wharf_gate_out='$WHARFOUT', estimated_full_delivery='$ESTFULLDELIVER', actual_full_deliver='$ACTFULLDELIVER', empty_returned_by='$EMPRETURNEDBY', empty_readyfor_returned='$EMPFORRETURNED', customs_Ref='$CUSTOMSREF', port_transport_ref='$PORTTRANSREF',slot_book_ref='$SLOTTRANSREF', do_release='$DORELEASE' ,trans_book_req='$DeliveryCartageAdvised',trans_actual_deliver='$DeliveryCartageCompleted',trans_deliverreq_from='$DeliveryRequiredFrom',trans_deliverreq_by='$DeliveryRequiredBy',trans_estimated_delivery='$EstimatedDelivery',trans_delivery_labour='$DeliveryLabourTime',trans_wait_time='$DeliveryTruckWaitTime',length='$TOTALLENGTH',width='$TOTALWIDTH',height='$TOTALHEIGHT'
								WHERE shipment_id=".$ship_idlast." AND containernumber='$CONTAINERNUMBER'";
								$insertRecContainer = sqlsrv_query($conn, $sqlUpdateRecord_Container); 
								}
								else{
								$sqlInsertRecord_Container = "INSERT INTO shipment_container
								(shipment_id,containershipnumber, containernumber, containertype, containerdeliverymode, containerdescription,fcl_unload,port_transport_booked,slot_date,wharf_gate_out,estimated_full_delivery,actual_full_deliver,empty_returned_by,empty_readyfor_returned,customs_Ref,port_transport_ref,slot_book_ref,do_release,trans_book_req,trans_actual_deliver,trans_deliverreq_from,trans_deliverreq_by,trans_estimated_delivery,trans_delivery_labour,trans_wait_time,length,width,height)
								Values(".$ship_idlast.",'" . $SHIPMENTKEY . "','" . $CONTAINERNUMBER . "','" . $CONTAINERTYPE . "','" . $DELIVERYMODE . "','" . $CATEGORYDESCRIPTION . "','".$FCLUNLOADFROMVESSEL."','".$ARRIVALCARTAGEADVISED."','".$SLOTDATE."','".$WHARFOUT."','".$ESTFULLDELIVER."','".$ACTFULLDELIVER."','".$EMPRETURNEDBY."','".$EMPFORRETURNED."','".$CUSTOMSREF."','".$PORTTRANSREF."','".$SLOTTRANSREF."','".$DORELEASE."','".$DeliveryCartageAdvised."','".$DeliveryCartageCompleted."','".$DeliveryRequiredFrom."','".$DeliveryRequiredBy."','".$EstimatedDelivery."','".$DeliveryLabourTime."','".$DeliveryTruckWaitTime."','".$TOTALLENGTH."','".$TOTALWIDTH."','".$TOTALHEIGHT."')";
								$insertRecContainer = sqlsrv_query($conn, $sqlInsertRecord_Container);	
								}        
							}	
						} 
						
						elseif ($CONTAINERctr == 1) {
							for ($c = 1; $c <= $CONTAINERctr; $c++) {
								$XPATH_CONTAINERNUMBER = jsonPath($universal_shipment, $path_ContainerCollection.".ContainerNumber");
								$CONTAINERNUMBER = $parser->encode($XPATH_CONTAINERNUMBER);
								$CONTAINERNUMBER = node_exist(getArrayName($CONTAINERNUMBER));
								
								$XPATH_CONTAINERTYPE = jsonPath($universal_shipment, $path_ContainerCollection.".ContainerType.Code");
								$CONTAINERTYPE = $parser->encode($XPATH_CONTAINERTYPE);
								$CONTAINERTYPE = node_exist(getArrayName($CONTAINERTYPE));
								
								$XPATH_DELIVERYMODE = jsonPath($universal_shipment, $path_ContainerCollection.".DeliveryMode");
								$DELIVERYMODE = $parser->encode($XPATH_DELIVERYMODE);
								$DELIVERYMODE = node_exist(getArrayName($DELIVERYMODE));
								
								$XPATH_CATEGORYDESCRIPTION = jsonPath($universal_shipment,$path_ContainerCollection.".ContainerType.Category.Description");
								$CATEGORYDESCRIPTION = $parser->encode($XPATH_CATEGORYDESCRIPTION);
								$CATEGORYDESCRIPTION = node_exist(getArrayName($CATEGORYDESCRIPTION));
								
								$XPATH_FCLUNLOADFROMVESSEL = jsonPath($universal_shipment, $path_ContainerCollection.".FCLUnloadFromVessel");
								$FCLUNLOADFROMVESSEL = $parser->encode($XPATH_FCLUNLOADFROMVESSEL);
								$FCLUNLOADFROMVESSEL = node_exist(getArrayName($FCLUNLOADFROMVESSEL));
								
								$XPATH_ARRIVALCARTAGEADVISED = jsonPath($universal_shipment, $path_ContainerCollection.".ArrivalCartageAdvised");
								$ARRIVALCARTAGEADVISED = $parser->encode($XPATH_ARRIVALCARTAGEADVISED);
								$ARRIVALCARTAGEADVISED = node_exist(getArrayName($ARRIVALCARTAGEADVISED));
								
								$XPATH_SLOTDATE = jsonPath($universal_shipment, $path_ContainerCollection.".ArrivalSlotDateTime");
								$SLOTDATE = $parser->encode($XPATH_SLOTDATE);
								$SLOTDATE = node_exist(getArrayName($SLOTDATE));
								
								$XPATH_WHARFOUT = jsonPath($universal_shipment, $path_ContainerCollection.".FCLWharfGateOut");
								$WHARFOUT = $parser->encode($XPATH_WHARFOUT);
								$WHARFOUT = node_exist(getArrayName($WHARFOUT));
								
								$XPATH_ESTFULLDELIVER = jsonPath($universal_shipment, $path_ContainerCollection.".ArrivalEstimatedDelivery");
								$ESTFULLDELIVER = $parser->encode($XPATH_ESTFULLDELIVER);
								$ESTFULLDELIVER = node_exist(getArrayName($ESTFULLDELIVER));
								
								$XPATH_ACTFULLDELIVER = jsonPath($universal_shipment, $path_ContainerCollection.".ArrivalCartageComplete");
								$ACTFULLDELIVER = $parser->encode($XPATH_ACTFULLDELIVER);
								$ACTFULLDELIVER = node_exist(getArrayName($ACTFULLDELIVER));
								
								$XPATH_EMPRETURNEDBY = jsonPath($universal_shipment, $path_ContainerCollection.".EmptyReturnedBy");
								$EMPRETURNEDBY = $parser->encode($XPATH_EMPRETURNEDBY);
								$EMPRETURNEDBY = node_exist(getArrayName($EMPRETURNEDBY));
								
								$XPATH_EMPFORRETURNED = jsonPath($universal_shipment, $path_ContainerCollection.".EmptyReadyForReturn");
								$EMPFORRETURNED = $parser->encode($XPATH_EMPFORRETURNED);
								$EMPFORRETURNED = node_exist(getArrayName($EMPFORRETURNED));
																
								$XPATH_CUSTOMSREF = jsonPath($universal_shipment, $path_ContainerCollection.".ImportDepotCustomsReference");
								$CUSTOMSREF = $parser->encode($XPATH_CUSTOMSREF);
								$CUSTOMSREF = node_exist(getArrayName($CUSTOMSREF));
								
								$XPATH_PORTTRANSREF = jsonPath($universal_shipment, $path_ContainerCollection.".ArrivalCartageRef");
								$PORTTRANSREF = $parser->encode($XPATH_PORTTRANSREF);
								$PORTTRANSREF = node_exist(getArrayName($PORTTRANSREF));
								
								$XPATH_SLOTTRANSREF = jsonPath($universal_shipment, $path_ContainerCollection.".ArrivalSlotReference");
								$SLOTTRANSREF = $parser->encode($XPATH_SLOTTRANSREF);
								$SLOTTRANSREF = node_exist(getArrayName($SLOTTRANSREF));
								
								$XPATH_DORELEASE = jsonPath($universal_shipment, $path_ContainerCollection.".ContainerImportDORelease");
								$DORELEASE = $parser->encode($XPATH_DORELEASE);
								$DORELEASE = node_exist(getArrayName($DORELEASE));
								
								$DeliveryCartageAdvised = jsonPath($universal_shipment, $path_UniversalSubShipment.".LocalProcessing.DeliveryCartageAdvised");
								$DeliveryCartageAdvised = $parser->encode($DeliveryCartageAdvised);
								$DeliveryCartageAdvised = node_exist(getArrayName($DeliveryCartageAdvised));
								
								$DeliveryCartageCompleted = jsonPath($universal_shipment, $path_UniversalSubShipment.".LocalProcessing.DeliveryCartageCompleted");
								$DeliveryCartageCompleted = $parser->encode($DeliveryCartageCompleted);
								$DeliveryCartageCompleted = node_exist(getArrayName($DeliveryCartageCompleted));
								
								$DeliveryRequiredFrom = jsonPath($universal_shipment, $path_UniversalSubShipment.".LocalProcessing.DeliveryRequiredFrom");
								$DeliveryRequiredFrom = $parser->encode($DeliveryRequiredFrom);
								$DeliveryRequiredFrom = node_exist(getArrayName($DeliveryRequiredFrom));
								
								$DeliveryRequiredBy = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryRequiredBy");
								$DeliveryRequiredBy = $parser->encode($DeliveryRequiredBy);
								$DeliveryRequiredBy = node_exist(getArrayName($DeliveryRequiredBy));
								
								$EstimatedDelivery = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.EstimatedDelivery");
								$EstimatedDelivery = $parser->encode($EstimatedDelivery);
								$EstimatedDelivery = node_exist(getArrayName($EstimatedDelivery));
								
								$DeliveryLabourTime = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryLabourTime");
								$DeliveryLabourTime = $parser->encode($DeliveryLabourTime);
								$DeliveryLabourTime = node_exist(getArrayName($DeliveryLabourTime));
								
								$DeliveryTruckWaitTime = jsonPath($universal_shipment,  $path_UniversalSubShipment.".LocalProcessing.DeliveryTruckWaitTime");
								$DeliveryTruckWaitTime = $parser->encode($DeliveryTruckWaitTime);
								$DeliveryTruckWaitTime = node_exist(getArrayName($DeliveryTruckWaitTime));
								
								$sqlSearchContainer = "Select * from shipment_container WHERE shipment_id=".$ship_idlast." AND containernumber='$CONTAINERNUMBER'";
								$searchContainer = sqlsrv_query($conn, $sqlSearchContainer);
								$ifContainerIDExist = sqlsrv_has_rows($searchContainer);
								
								if($ifContainerIDExist == 'true')
							    {
							    $sqlUpdateRecord_Container = "Update shipment_container SET
								containernumber='$CONTAINERNUMBER', containertype='$CONTAINERTYPE', containerdeliverymode='$DELIVERYMODE', containerdescription='$CATEGORYDESCRIPTION', fcl_unload='$FCLUNLOADFROMVESSEL', port_transport_booked='$ARRIVALCARTAGEADVISED', slot_date='$SLOTDATE', wharf_gate_out='$WHARFOUT', estimated_full_delivery='$ESTFULLDELIVER', actual_full_deliver='$ACTFULLDELIVER', empty_returned_by='$EMPRETURNEDBY', empty_readyfor_returned='$EMPFORRETURNED', customs_Ref='$CUSTOMSREF', port_transport_ref='$PORTTRANSREF',slot_book_ref='$SLOTTRANSREF', do_release='$DORELEASE'  ,trans_book_req='$DeliveryCartageAdvised',trans_actual_deliver='$DeliveryCartageCompleted',trans_deliverreq_from='$DeliveryRequiredFrom',trans_deliverreq_by='$DeliveryRequiredBy',trans_estimated_delivery='$EstimatedDelivery',trans_delivery_labour='$DeliveryLabourTime',trans_wait_time='$DeliveryTruckWaitTime',length='$TOTALLENGTH',width='$TOTALWIDTH',height='$TOTALHEIGHT'
								WHERE shipment_id=".$ship_idlast." AND containernumber='$CONTAINERNUMBER'";
								$insertRecContainer = sqlsrv_query($conn, $sqlUpdateRecord_Container); 
								}
								else{
								$sqlInsertRecord_Container = "INSERT INTO shipment_container
								(shipment_id,containershipnumber, containernumber, containertype, containerdeliverymode, containerdescription,fcl_unload,port_transport_booked,slot_date,wharf_gate_out,estimated_full_delivery,actual_full_deliver,empty_returned_by,empty_readyfor_returned,customs_Ref,port_transport_ref,slot_book_ref,do_release,trans_book_req,trans_actual_deliver,trans_deliverreq_from,trans_deliverreq_by,trans_estimated_delivery,trans_delivery_labour,trans_wait_time,length,width,height)
								Values(".$ship_idlast.",'" . $SHIPMENTKEY . "','" . $CONTAINERNUMBER . "','" . $CONTAINERTYPE . "','" . $DELIVERYMODE . "','" . $CATEGORYDESCRIPTION . "','".$FCLUNLOADFROMVESSEL."','".$ARRIVALCARTAGEADVISED."','".$SLOTDATE."','".$WHARFOUT."','".$ESTFULLDELIVER."','".$ACTFULLDELIVER."','".$EMPRETURNEDBY."','".$EMPFORRETURNED."','".$CUSTOMSREF."','".$PORTTRANSREF."','".$SLOTTRANSREF."','".$DORELEASE."','".$DeliveryCartageAdvised."','".$DeliveryCartageCompleted."','".$DeliveryRequiredFrom."','".$DeliveryRequiredBy."','".$EstimatedDelivery."','".$DeliveryLabourTime."','".$DeliveryTruckWaitTime."','".$TOTALLENGTH."','".$TOTALWIDTH."','".$TOTALHEIGHT."')";
								$insertRecContainer = sqlsrv_query($conn, $sqlInsertRecord_Container);	
					 
								}  
							}
						}
						 
						process_shipment($SHIPMENTKEY,$client_email,$ship_idlast,$webservicelink,$service_user,$service_password,$server_id,$enterprise_id,$auth,$company_code);
						if(!file_exists($destination_path.$filename)){
						rename($filename, $destination_path . pathinfo($filename, PATHINFO_BASENAME));
						file_log($SHIPMENTKEY,$filename,$CLIENT_ID);
						}
					}
				}
                //END OF DATA MANAGEMENT
			}
			else
			{
			$destination_pathERR = "E:/A2BFREIGHT_MANAGER/$client_email/CW_ERROR/";						
			if(!file_exists($destination_pathERR.$filename)){
			rename($filename, $destination_pathERR . pathinfo($filename, PATHINFO_BASENAME));
				}
			}
		}
		/*call order module details*/
		require_once('customs.php');
		require_once('arinvoice.php');
		require_once('order.php');
	}
} 
else{die("eAdaptor not found");}
header("HTTP/1.1 200 OK");

?>
