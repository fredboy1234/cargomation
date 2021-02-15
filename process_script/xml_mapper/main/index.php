<?php 
/**
* Class and Function List:
* Function list:
* - process_shipment()
* - getArrayName()
* - Base64_Decoder()
*/

require_once ('json.php');
require_once ('jsonpath-0.8.1.php');
require_once ('connection.php');
header('Content-Type: text/plain');
set_time_limit(0);
 
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

	function process_shipment(
		$key,
		$client_email,
		$ship_id,
		$webservicelink,
		$service_user,
		$service_password,
		$server_id,
		$enterprise_id,
		$auth,
		$company_code
	)
	{
		$serverName = "a2bserver.database.windows.net";
		$connectionInfo = array("Database" => "a2bfreighthub_db","UID" => "A2B_Admin","PWD" => "v9jn9cQ9dF7W");
		$conn = sqlsrv_connect($serverName, $connectionInfo);
		
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
		$parser = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		curl_setopt($curl_, CURLOPT_SSL_VERIFYPEER, false);
		$document_request = curl_exec($curl_);
		curl_close($curl_);
		$xml_docs = simplexml_load_string($document_request);
		$json_documentrequest = json_encode($xml_docs, JSON_PRETTY_PRINT);
		$json_xpathdoc = json_decode($json_documentrequest, true);

		$doc_status = jsonPath($json_xpathdoc, "$.Status");
		$doc_status = $parser->encode($doc_status);
		$doc_status = getArrayName($doc_status);

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
					$SingleAttach_ctr = $parser->encode($xpath_AttachedCountSingle);
					$SingleAttach_ctrb64 = $parser->encode($xpath_AttachedB64);
					$DocType = $parser->encode($xpath_DocType);
					$Saved_date = $parser->encode($xpath_SavedUtc);
					$Saved_EventTime = $parser->encode($xpath_SavedEventTime);
					$Saved_By = $parser->encode($xpath_SavedBy);
					$ctr_1 = getArrayName($SingleAttach_ctr);
					$ctr_b64 = getArrayName($SingleAttach_ctrb64);
					$get_valDocType_ = getArrayName($DocType);
					$get_valSavedDate = getArrayName($Saved_date);
					$get_Saved_By = getArrayName($Saved_By);
					$get_Saved_EventTime = getArrayName($Saved_EventTime);

					$ifdocexist = "SELECT *,
					dbo.document_base64.img_data
					FROM   dbo.document_base64
					INNER JOIN dbo.document
					ON dbo.document_base64.document_id = dbo.document.id
					WHERE  dbo.document.NAME = '$ctr_1'
					AND dbo.document_base64.img_data = '$ctr_b64'
					AND dbo.document.type = '$get_valDocType_'
					AND dbo.document.shipment_id='$ship_id'
					";
					$ifdocexistqry = sqlsrv_query($conn, $ifdocexist);
					$ifdocexistres = sqlsrv_has_rows($ifdocexistqry);

					if ($ifdocexistres === false) {
					$sqlInsertRecord = "INSERT INTO document
					(shipment_id ,shipment_num, type, name, saved_by, saved_date, event_date, upload_src ) Values
					($ship_id,'" . $key . "','" . $get_valDocType_ . "','" . $ctr_1 . "','" . $get_Saved_By . "','" . $get_valSavedDate . "','" . $get_Saved_EventTime . "','cargowise')";
						$execRecord = sqlsrv_query($conn, $sqlInsertRecord);
						$sql_getlastdocID = "SELECT IDENT_CURRENT('dbo.document') as document_id;";
						$execRecord_getlastdocID = sqlsrv_query($conn, $sql_getlastdocID);
						while ($row_docid = sqlsrv_fetch_array($execRecord_getlastdocID, SQLSRV_FETCH_ASSOC)) {
							$doc_idlast1 = $row_docid['document_id'];
						}
					
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
					break;
				} else {
					$xpath_AttachedCountSingle = jsonPath($json_xpathdoc, $path_AttachedDocument."[$attach].FileName");
					$xpath_AttachedB64 = jsonPath($json_xpathdoc, $path_AttachedDocument."[$attach].ImageData");
					$xpath_DocType = jsonPath($json_xpathdoc,$path_AttachedDocument."[$attach].Type.Code");
					$xpath_SavedUtc = jsonPath($json_xpathdoc, $path_AttachedDocument."[$attach].SaveDateUTC");
					$xpath_SavedBy = jsonPath($json_xpathdoc, $path_AttachedDocument."[$attach].SavedBy.Code");
					$xpath_SavedEventTime = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.EventTime");
					$SingleAttach_ctr = $parser->encode($xpath_AttachedCountSingle);
					$SingleAttach_ctrb64 = $parser->encode($xpath_AttachedB64);
					$DocType = $parser->encode($xpath_DocType);
					$Saved_date = $parser->encode($xpath_SavedUtc);
					$Saved_EventTime = $parser->encode($xpath_SavedEventTime);
					$Saved_By = $parser->encode($xpath_SavedBy);
					$ctr_1 = getArrayName($SingleAttach_ctr);
					$ctr_b64 = getArrayName($SingleAttach_ctrb64);
					$get_valDocType_ = getArrayName($DocType);
					$get_valSavedDate = getArrayName($Saved_date);
					$get_Saved_By = getArrayName($Saved_By);
					$get_Saved_EventTime = getArrayName($Saved_EventTime);

					$ifdocexist = "SELECT *,
					dbo.document_base64.img_data
					FROM   dbo.document_base64
					INNER JOIN dbo.document
					ON dbo.document_base64.document_id = dbo.document.id
					WHERE  dbo.document.NAME = '$ctr_1'
					AND dbo.document_base64.img_data = '$ctr_b64'
					AND dbo.document.type = '$get_valDocType_' 
					";
					$ifdocexistqry = sqlsrv_query($conn, $ifdocexist);
					$ifdocexistres = sqlsrv_has_rows($ifdocexistqry);

					if ($ifdocexistres === false) {
						echo $sqlInsertRecord = "INSERT INTO document
						(shipment_id ,shipment_num, type, name, saved_by, saved_date, event_date, upload_src ) Values
						($ship_id,'" . $key . "','" . $get_valDocType_ . "','" . $ctr_1 . "','" . $get_Saved_By . "','" . $get_valSavedDate . "','" . $get_Saved_EventTime . "','cargowise')";
						$execRecord = sqlsrv_query($conn, $sqlInsertRecord);
						$sql_getlastdocID = "SELECT IDENT_CURRENT('dbo.document') as document_id;";
						$execRecord_getlastdocID = sqlsrv_query($conn, $sql_getlastdocID);
						while ($row_docid = sqlsrv_fetch_array($execRecord_getlastdocID, SQLSRV_FETCH_ASSOC)) {
							$doc_idlast1 = $row_docid['document_id'];
						}
					
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
			}
		} else 
		{
			echo "no edocs found";
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
	
	
	function Base64_Decoder($val, $valName, $id, $pathName, $shipkey)
	{
		$path = "E:/A2BFREIGHT_MANAGER/$id/CW_FILE/$shipkey/$pathName/";
		$b64 = str_replace(array('["', '"]', '\/'), array("", "", "/"), $val);
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
			file_put_contents($path . $valName, base64_decode($b64));
		} else {
			file_put_contents($path . $valName, base64_decode($b64));
		}
	}
	$sqluser_info = "SELECT TOP (1) * FROM [dbo].[user_info] WHERE [user_id] = '$CLIENT_ID'";
	$execRecord_userinfo = sqlsrv_query($conn, $sqluser_info);
	$return_user = sqlsrv_has_rows($execRecord_userinfo);
	if ($return_user == true) {
		while ($row_user = sqlsrv_fetch_array($execRecord_userinfo, SQLSRV_FETCH_ASSOC)) {
			$client_email = $row_user['email'];
		}
		foreach (glob("E:/A2BFREIGHT_MANAGER/$client_email/CW_XML/*") as $filename) {
			$path_DataSource ="$.Body.UniversalShipment.Shipment.DataContext.DataSourceCollection.DataSource";
			$CONSOLNUMBER = "";
			$SHIPMENTKEY = "";
			$WAYBILLNUMBER = "";
			$HOUSEWAYBILLNUMBER = "";
			$TRANSMODE = "";
			$VESSELNAME = "";
			$VOYAGEFLIGHTNO = "";
			$VESSELLOYDSIMO = "";
			$TRANS_ETA = "";
			$TRANS_ETD = "";
			$PLACEOFDELIVERY = "";
			$PLACEOFRECEIPT = "";
			$CONSIGNEE = "";
			$CONSIGNOR = "";
			$PATH_SENDINGAGENT = "";
			$PATH_RECEIVINGAGENT = "";
			$RECEIVINGAGENTADDRESS = "";
			$SENDINGAGENTADDRESS = "";
			$CONSIGNEEADDRESS = "";
			$PATH_CONSIGNORADDRESS = "";
			$parser = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
			$myxmlfilecontent = file_get_contents($filename);
			$xml = simplexml_load_string($myxmlfilecontent);
			$universalshipment = json_encode($xml, JSON_PRETTY_PRINT);
			$universal_shipment = json_decode($universalshipment, true);

			$XPATH_SHIPMENTTYPE = jsonPath($universal_shipment, $path_DataSource.".Type");
			$SHIPMENTTYPE = $parser->encode($XPATH_SHIPMENTTYPE);
		    $SHIPMENTTYPE = getArrayName($SHIPMENTTYPE);

			if ($SHIPMENTTYPE != 'false') {
				if ($SHIPMENTTYPE == "ForwardingShipment") {
					$XPATH_SHIPMENTKEY = jsonPath($universal_shipment, $path_DataSource.".Key");
					$SHIPMENTKEY = $parser->encode($XPATH_SHIPMENTKEY);
					$SHIPMENTKEY = getArrayName($SHIPMENTKEY);
				} elseif ($SHIPMENTTYPE == "ForwardingConsol") {
					$XPATH_CONSOLNUMBER = jsonPath($universal_shipment, $path_DataSource.".Key");
					$CONSOLNUMBER = $parser->encode($XPATH_CONSOLNUMBER);
					$CONSOLNUMBER = getArrayName($CONSOLNUMBER);
				}
				elseif ($SHIPMENTTYPE == "CustomsDeclaration") {
					$XPATH_SHIPMENTKEY = jsonPath($universal_shipment, $path_DataSource.".Key");
					$SHIPMENTKEY = $parser->encode($XPATH_SHIPMENTKEY);
					$SHIPMENTKEY = getArrayName($SHIPMENTKEY);
				}
				
				else {
					if ($CONSOLNUMBER == "" || is_null($CONSOLNUMBER) == true) {
						$CONSOLNUMBER = "";
					}
				}
			} elseif ($SHIPMENTTYPE == 'false') {
				for ($k = 0; $k <= 2; $k++) {
					$XPATH_SHIPMENTTYPE_ = jsonPath($universal_shipment, $path_DataSource."[$k].Type");
					$SHIPMENTTYPE_ = $parser->encode($XPATH_SHIPMENTTYPE_);
					$SHIPMENTTYPE_ = getArrayName($SHIPMENTTYPE_);
					if ($SHIPMENTTYPE_ != 'false') {
						if ($SHIPMENTTYPE_ == "ForwardingShipment") {
							$XPATH_SHIPMENTKEY = jsonPath($universal_shipment, $path_DataSource."[$k].Key");
							$SHIPMENTKEY = $parser->encode($XPATH_SHIPMENTKEY);
							$SHIPMENTKEY = getArrayName($SHIPMENTKEY);
						} elseif ($SHIPMENTTYPE_ == "ForwardingConsol") {
							$XPATH_CONSOLNUMBER = jsonPath($universal_shipment, $path_DataSource."[$k].Key");
							$CONSOLNUMBER = $parser->encode($XPATH_CONSOLNUMBER);
							$CONSOLNUMBER = getArrayName($CONSOLNUMBER);
						}
						elseif ($SHIPMENTTYPE_ == "CustomsDeclaration") {
							$XPATH_SHIPMENTKEY = jsonPath($universal_shipment, $path_DataSource."[$k].Key");
							$SHIPMENTKEY = $parser->encode($XPATH_SHIPMENTKEY);
							$SHIPMENTKEY = getArrayName($SHIPMENTKEY);
						}
					} else {
						if ($SHIPMENTKEY == "") {
							$SHIPMENTKEY = "";
						}
				if ($CONSOLNUMBER == "" || is_null($CONSOLNUMBER) == true) {
							$CONSOLNUMBER = "";
						}
					}
				}
			}
	
		
      
			 
			if ($CONSOLNUMBER == "" || $CONSOLNUMBER != "") {
                
				//XML PATH
				$path_UniversalShipment = "$.Body.UniversalShipment.Shipment";
				$path_UniversalShipmentContext = "$.Body.UniversalShipment.Shipment.DataContext";
				$path_UniversalSubShipment = "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment";
				$path_SubUniversalSubShipment = "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection";
				$path_AddressUniversalShipment = "$.Body.UniversalShipment.Shipment.OrganizationAddressCollection";
				$path_TransportLegCollection = "$.Body.UniversalShipment.Shipment.TransportLegCollection.TransportLeg";
				$path_ContainerCollection = "$.Body.UniversalShipment.Shipment.ContainerCollection.Container";
				
				if($CONSOLNUMBER == ""){
					$path_SubUniversalSubShipment = "$.Body.UniversalShipment.Shipment.OrganizationAddressCollection";
				}
				else{
					$path_SubUniversalSubShipment = "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection";
				}
				
				//GET WAYBLL NUMBER
				$XPATH_WAYBILLNUMBER = jsonPath($universal_shipment, $path_UniversalShipment.".WayBillNumber");
				$WAYBILLNUMBER = $parser->encode($XPATH_WAYBILLNUMBER);
				$WAYBILLNUMBER = getArrayName($WAYBILLNUMBER);
                //GET HOUSEBILL NUMBER
				$XPATH_HOUSEWAYBILLNUMBER = jsonPath($universal_shipment, $path_UniversalSubShipment.".WayBillNumber");
				$HOUSEWAYBILLNUMBER = $parser->encode($XPATH_HOUSEWAYBILLNUMBER);
				$HOUSEWAYBILLNUMBER = getArrayName($HOUSEWAYBILLNUMBER);
                //GET TRANSPORT MODE
				$OrganizationAddress = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress");
				$OrganizationAddress_ctr = $OrganizationAddress;
				$XPATH_TRANSMODE = jsonPath($universal_shipment, $path_TransportLegCollection.".TransportMode");
				$TRANSMODE = $parser->encode($XPATH_TRANSMODE);
				$TRANSMODE = getArrayName($TRANSMODE);
				$XPATH_SHIP_ETD = jsonPath($universal_shipment, $path_TransportLegCollection.".EstimatedDeparture");
				$TRANS_ETD = $parser->encode($XPATH_SHIP_ETD);
				$TRANS_ETD = getArrayName($TRANS_ETD);
				$XPATH_SHIP_ETA = jsonPath($universal_shipment, $path_TransportLegCollection.".EstimatedArrival");
				$TRANS_ETA = $parser->encode($XPATH_SHIP_ETA);
				$TRANS_ETA = getArrayName($TRANS_ETA);
				$XPATH_SHIP_TRIGGERDATE = jsonPath($universal_shipment, $path_UniversalShipmentContext.".TriggerDate");
				$SHIP_TRIGGERDATE = $parser->encode($XPATH_SHIP_TRIGGERDATE);
				$SHIP_TRIGGERDATE = getArrayName($SHIP_TRIGGERDATE);
				if($SHIP_TRIGGERDATE == "false"){
					$SHIP_TRIGGERDATE = date("Y-m-d h:i:s");
				}
				
                //GET VESSELLOYDSIMO
				$XPATH_VESSELLOYDSIMO = jsonPath($universal_shipment, $path_TransportLegCollection.".VesselLloydsIMO");
				$VESSELLOYDSIMO = $parser->encode($XPATH_VESSELLOYDSIMO);
				$VESSELLOYDSIMO = getArrayName($VESSELLOYDSIMO);
				if ($XPATH_TRANSMODE == false || $XPATH_SHIP_ETD == false || $XPATH_SHIP_ETA == false) {
					$XPATH_TRANSMODE = jsonPath($universal_shipment, $path_TransportLegCollection."[0].TransportMode");
					$TRANSMODE = $parser->encode($XPATH_TRANSMODE);
					$TRANSMODE = getArrayName($TRANSMODE);
					$XPATH_SHIP_ETD = jsonPath($universal_shipment, $path_TransportLegCollection."[0].EstimatedDeparture");
					$TRANS_ETD = $parser->encode($XPATH_SHIP_ETD);
					$TRANS_ETD = getArrayName($TRANS_ETD);
					$XPATH_SHIP_ETA = jsonPath($universal_shipment, $path_TransportLegCollection."[0].EstimatedArrival");
					$TRANS_ETA = $parser->encode($XPATH_SHIP_ETA);
					$TRANS_ETA = getArrayName($TRANS_ETA);
					$XPATH_VESSELLOYDSIMO = jsonPath($universal_shipment, $path_TransportLegCollection."[0].VesselLloydsIMO");
					$VESSELLOYDSIMO = $parser->encode($XPATH_VESSELLOYDSIMO);
					$VESSELLOYDSIMO = getArrayName($VESSELLOYDSIMO);
				}
                //GET VESSEL NAME
				$XPATH_VESSELNAME = jsonPath($universal_shipment, $path_UniversalShipment.".VesselName");
				$VESSELNAME = $parser->encode($XPATH_VESSELNAME);
				$VESSELNAME = getArrayName($VESSELNAME);
                //GET VOYAGE#
				$XPATH_VOYAGEFLIGHTNO = jsonPath($universal_shipment, $path_UniversalShipment.".VoyageFlightNo");
				$VOYAGEFLIGHTNO = $parser->encode($XPATH_VOYAGEFLIGHTNO);
				$VOYAGEFLIGHTNO = getArrayName($VOYAGEFLIGHTNO);
                //GET PLACEOFDELIVERY
				$XPATH_PLACEOFDELIVERY = jsonPath($universal_shipment, $path_UniversalShipment.".PlaceOfDelivery.Name");
				$PLACEOFDELIVERY = $parser->encode($XPATH_PLACEOFDELIVERY);
				$PLACEOFDELIVERY = getArrayName($PLACEOFDELIVERY);
                //GET PLACEOFRECEIPT
				$XPATH_PLACEOFRECEIPT = jsonPath($universal_shipment, $path_UniversalShipment.".PlaceOfReceipt.Name");
				$PLACEOFRECEIPT = $parser->encode($XPATH_PLACEOFRECEIPT);
				$PLACEOFRECEIPT = getArrayName($PLACEOFRECEIPT);
                //GET CONTAINER COUNT
				$XPATH_CONTAINERCOUNT = jsonPath($universal_shipment, $path_UniversalShipment.".ContainerCount");
				$CONTAINERCOUNT = $parser->encode($XPATH_CONTAINERCOUNT);
				$CONTAINERctr = getArrayName($CONTAINERCOUNT);
				$CONTAINERctr = (int)$CONTAINERctr;
				if ($CONTAINERctr == 1) {
					$CONTAINERctr = 1;
				}
				$OrganizationAddress = jsonPath(
					$universal_shipment, $path_SubUniversalSubShipment.".OrganizationAddress"
				);
				$OrganizationAddress_ctr = $OrganizationAddress;
				if ($OrganizationAddress_ctr != false) {
					$OrganizationAddress = jsonPath(
						$universal_shipment, $path_SubUniversalSubShipment.".OrganizationAddress"
					);
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
					$PATH_ADDRESSTYPE = $parser->encode($XPATH_ADDRESSTYPE);
					$PATH_ADDRESSTYPE = getArrayName($PATH_ADDRESSTYPE);
					
					$XPATH_EMAIL= jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].Email");
					$XPATH_EMAIL_GLOBAL = $parser->encode($XPATH_EMAIL);
					$XPATH_EMAIL_GLOBAL = getArrayName($XPATH_EMAIL_GLOBAL);
					$XPATH_ORGCODE_GLOBAL = $parser->encode($XPATH_COMPANYNAME);
					$XPATH_ORGCODE_GLOBAL = getArrayName($XPATH_ORGCODE_GLOBAL);
					
					
					if ($PATH_ADDRESSTYPE == "ConsigneeDocumentaryAddress") {
						
						$XPATH_COMPANYNAME = $parser->encode($XPATH_COMPANYNAME);
						$PATH_CONSIGNEE = getArrayName($XPATH_COMPANYNAME);
						$XPATH_ADDRESS1 = $parser->encode($XPATH_ADDRESS1);
						$PATH_CONSIGNEEADDRESS1 = getArrayName($XPATH_ADDRESS1);
						$XPATH_STATE = $parser->encode($XPATH_STATE);
						$PATH_CONSIGNEESTATE = getArrayName($XPATH_STATE);
						$XPATH_POSTCODE = $parser->encode($XPATH_POSTCODE);
						$PATH_CONSIGNEEPOSTCODE = getArrayName($XPATH_POSTCODE);
						$XPATH_COUNTRY = $parser->encode($XPATH_COUNTRY);
						$PATH_CONSIGNEECOUNTRY = getArrayName($XPATH_COUNTRY);
						$CONSIGNEE = $parser->encode($XPATH_ORGANIZATIONCODE);
						$CONSIGNEE = getArrayName($CONSIGNEE);
						if ($CONSIGNEE == 'false') {
							$CONSIGNEE = "";
						}
						$CONSIGNEEADDRESS = $PATH_CONSIGNEEADDRESS1 . ", " . $PATH_CONSIGNEESTATE . ", " . $PATH_CONSIGNEEPOSTCODE . ", " . $PATH_CONSIGNEECOUNTRY;
					}

					elseif ($PATH_ADDRESSTYPE == "ConsignorDocumentaryAddress") {
						$XPATH_COMPANYNAME = $parser->encode($XPATH_COMPANYNAME);
						$PATH_CONSIGNOR = getArrayName($XPATH_COMPANYNAME);
						$XPATH_ADDRESS1 = $parser->encode($XPATH_ADDRESS1);
						$PATH_CONSIGNORADDRESS1 = getArrayName($XPATH_ADDRESS1);
						$XPATH_STATE = $parser->encode($XPATH_STATE);
						$PATH_CONSIGNORSTATE = getArrayName($XPATH_STATE);
						$XPATH_POSTCODE = $parser->encode($XPATH_POSTCODE);
						$PATH_CONSIGNORPOSTCODE = getArrayName($XPATH_POSTCODE);
						$XPATH_COUNTRY = $parser->encode($XPATH_COUNTRY);
						$PATH_CONSIGNORCOUNTRY = getArrayName($XPATH_COUNTRY);
						$CONSIGNOR = $parser->encode($XPATH_ORGANIZATIONCODE);
						$CONSIGNOR = getArrayName($CONSIGNOR);
						if ($CONSIGNOR == 'false') {
							$CONSIGNOR = "";
						}
						$CONSIGNORADDRESS = $PATH_CONSIGNORADDRESS1 . ", " . $PATH_CONSIGNORSTATE . ", " . $PATH_CONSIGNORPOSTCODE . ", " . $PATH_CONSIGNORCOUNTRY;
					}
				}
				
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
						$PATH_ADDRESSTYPE_ = getArrayName($PATH_ADDRESSTYPE_);
						
						$XPATH_EMAIL_GLOBAL_ = $parser->encode($EMAIL);
						$XPATH_EMAIL_GLOBAL_ = getArrayName($XPATH_EMAIL_GLOBAL_);
						$XPATH_ORGCODE_GLOBAL = $parser->encode($COMPANYNAME);
						$XPATH_ORGCODE_GLOBAL = getArrayName($XPATH_ORGCODE_GLOBAL);

						if ($PATH_ADDRESSTYPE_ == "SendingForwarderAddress") {
							$XPATH_ORGANIZATIONCODE_ = $parser->encode($ORGANIZATIONCODE);
							$PATH_SENDINGAGENT = getArrayName($XPATH_ORGANIZATIONCODE_);
							$XPATH_ADDRESS1 = $parser->encode($ADDRESS1);
							$PATH_SENDINGAGENTADDRESS1 = getArrayName($XPATH_ADDRESS1);
							$XPATH_ADDRESS2 = $parser->encode($ADDRESS2);
							$PATH_SENDINGAGENTADDRESS2 = getArrayName($XPATH_ADDRESS2);
							$XPATH_ADDRESSCODE = $parser->encode($ADDRESSCODE);
							$PATH_SENDINGAGENTADDRESSCODE = getArrayName($XPATH_ADDRESSCODE);
							$XPATH_PORTNAME = $parser->encode($PORTNAME);
							$PATH_SENDINGAGENTPORTNAME = getArrayName($XPATH_PORTNAME);
							$XPATH_STATE = $parser->encode($STATE);
							$PATH_SENDINGAGENTSTATE = getArrayName($XPATH_STATE);
							$SENDINGAGENTADDRESS = $PATH_SENDINGAGENTADDRESS1 . ", " . $PATH_SENDINGAGENTADDRESSCODE . ", " . $PATH_SENDINGAGENTADDRESS2 . ", " . $PATH_SENDINGAGENTPORTNAME . ", " . $PATH_SENDINGAGENTSTATE;
							
						} elseif ($PATH_ADDRESSTYPE_ == "ReceivingForwarderAddress") {
							$XPATH_ORGANIZATIONCODE_ = $parser->encode($ORGANIZATIONCODE);
							$PATH_RECEIVINGAGENT = getArrayName($XPATH_ORGANIZATIONCODE_);
							$XPATH_ADDRESS1 = $parser->encode($ADDRESS1);
							$PATH_RECEIVINGAGENTADDRESS1 = getArrayName($XPATH_ADDRESS1);
							$XPATH_ADDRESS2 = $parser->encode($ADDRESS2);
							$PATH_RECEIVINGAGENTADDRESS2 = getArrayName($XPATH_ADDRESS2);
							$XPATH_ADDRESSCODE = $parser->encode($ADDRESSCODE);
							$PATH_RECEIVINGAGENTADDRESSCODE = getArrayName($XPATH_ADDRESSCODE);
							$XPATH_PORTNAME = $parser->encode($PORTNAME);
							$PATH_RECEIVINGAGENTPORTNAME = getArrayName($XPATH_PORTNAME);
							$XPATH_STATE = $parser->encode($STATE);
							$PATH_RECEIVINGAGENTSTATE = getArrayName($XPATH_STATE);
							$RECEIVINGAGENTADDRESS = $PATH_RECEIVINGAGENTADDRESS1 . ", " . $PATH_RECEIVINGAGENTADDRESSCODE . ", " . $PATH_RECEIVINGAGENTADDRESS2 . ", " . $PATH_RECEIVINGAGENTPORTNAME . ", " . $PATH_RECEIVINGAGENTSTATE;
						}
					}
				
            
				if (!empty($SHIPMENTKEY) || $SHIPMENTKEY <> "") {
				$sql = "SELECT * FROM dbo.shipment WHERE dbo.shipment.shipment_num = '$SHIPMENTKEY' AND dbo.shipment.user_id ='$CLIENT_ID'";
				$qryResultShipID = sqlsrv_query($conn, $sql);
				$ifShipIDExist = sqlsrv_has_rows($qryResultShipID);
				$destination_path = "E:/A2BFREIGHT_MANAGER/$client_email/CW_SUCCESS/";
				
					if ($ifShipIDExist == false) {
						if ($TRANS_ETA == 'false' || $TRANS_ETD == 'false') {
							 $TRANS_ETA = null;
							 $TRANS_ETD = null;
						}
						
				$sqlInsertRecord = "INSERT INTO shipment
                (user_id ,console_id, shipment_num, master_bill, house_bill, transport_mode,
                vessel_name, voyage_flight_num, vesslloyds, eta, etd, place_delivery, place_receipt,
				consignee, consignor, sending_agent, receiving_agent, receiving_agent_addr, sending_agent_addr, consignee_addr, consignor_addr, trigger_date)
                Values(" . $CLIENT_ID . ",'" . $CONSOLNUMBER . "','" . $SHIPMENTKEY . "','" . $WAYBILLNUMBER . "','" . $HOUSEWAYBILLNUMBER . "','" . $TRANSMODE . "','" . $VESSELNAME . "','" . $VOYAGEFLIGHTNO . "','" . $VESSELLOYDSIMO . "','" . $TRANS_ETA . "','" . $TRANS_ETD . "','" . $PLACEOFDELIVERY . "','" . $PLACEOFRECEIPT . "',
				'" . $CONSIGNEE . "','" . $CONSIGNOR . "','" . $PATH_SENDINGAGENT . "','" . $PATH_RECEIVINGAGENT . "','" . $RECEIVINGAGENTADDRESS . "','" . $SENDINGAGENTADDRESS . "','" . $CONSIGNEEADDRESS . "','" . $CONSIGNORADDRESS . "','" . $SHIP_TRIGGERDATE . "')";
						$insertRec = sqlsrv_query($conn, $sqlInsertRecord);
						$sql_getlastshipID = "SELECT top 1 MAX(Id) as ship_id FROM shipment";
						$execRecord_getlastshipID = sqlsrv_query($conn, $sql_getlastshipID);
						while ($row_shipid = sqlsrv_fetch_array($execRecord_getlastshipID, SQLSRV_FETCH_ASSOC)) {
							$ship_idlast = $row_shipid['ship_id'];
						}
						

					for ($a = 0; $a <= $OrganizationAddress_ctr - 1; $a++) {
					$XPATH_COMPANYNAME = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].OrganizationCode");
					$XPATH_ADDRESSTYPE = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].AddressType");
					$XPATH_COMPANY = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].CompanyName");
					$XPATH_EMAIL= jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].Email");
					$PATH_ADDRESSTYPE = $parser->encode($XPATH_ADDRESSTYPE);
					$PATH_ADDRESSTYPE = getArrayName($PATH_ADDRESSTYPE);
					$XPATH_EMAIL_GLOBAL = $parser->encode($XPATH_EMAIL);
					$XPATH_EMAIL_GLOBAL = getArrayName($XPATH_EMAIL_GLOBAL);
					$XPATH_ORGCODE_GLOBAL = $parser->encode($XPATH_COMPANYNAME);
					$XPATH_ORGCODE_GLOBAL = getArrayName($XPATH_ORGCODE_GLOBAL);
					$XPATH_COMPANY = $parser->encode($XPATH_COMPANY);
					$XPATH_COMPANY = getArrayName($XPATH_COMPANY);
		
					
					if(is_null($XPATH_EMAIL_GLOBAL) == false || $XPATH_EMAIL_GLOBAL != 'false'){
					 $sql_contact = "SELECT * FROM dbo.shipment_contacts WHERE dbo.shipment_contacts.shipment_id = '$ship_idlast' AND dbo.shipment_contacts.address_type ='$PATH_ADDRESSTYPE' AND dbo.shipment_contacts.organization_code = '$XPATH_ORGCODE_GLOBAL' AND email='$XPATH_EMAIL_GLOBAL'";
					$qryResultContact = sqlsrv_query($conn, $sql_contact);
					$ifContactExist = sqlsrv_has_rows($qryResultContact);
					
					if($ifContactExist === false && strlen($XPATH_EMAIL_GLOBAL) > 5 && $PATH_ADDRESSTYPE == "ConsigneeDocumentaryAddress"){
					   $sql_Insert_contact = "INSERT INTO FROM dbo.shipment_contacts (shipment_id,address_type,organization_code,email,is_default,company_name)
					   VALUES ('".$ship_idlast."','".$PATH_ADDRESSTYPE."','".$XPATH_ORGCODE_GLOBAL."','".$XPATH_EMAIL_GLOBAL."','N','".$XPATH_COMPANY."');";
					   $qry_Insert_contact = sqlsrv_query($conn, $sql_Insert_contact);
					}
				}
			}
						
				for ($b = 0; $b <= $OrganizationAddress_ctr1 - 1; $b++) {
					    $XPATH_COMPANYNAME = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$a].OrganizationCode");
						$ADDRESSTYPE = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].AddressType");
						$EMAIL = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].Email");
						$XPATH_EMAIL_GLOBAL_ = $parser->encode($EMAIL);
						$XPATH_EMAIL_GLOBAL_ = getArrayName($XPATH_EMAIL_GLOBAL_);
						$XPATH_ORGCODE_GLOBAL = $parser->encode($COMPANYNAME);
						$XPATH_ORGCODE_GLOBAL = getArrayName($XPATH_ORGCODE_GLOBAL);
						$PATH_ADDRESSTYPE_ = $parser->encode($ADDRESSTYPE);
						$PATH_ADDRESSTYPE_ = getArrayName($PATH_ADDRESSTYPE_);
						
						
						$XPATH_COMPANY_ = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$a].CompanyName");
						$XPATH_COMPANY_ = $parser->encode($XPATH_COMPANY_);
						$XPATH_COMPANY_ = getArrayName($XPATH_COMPANY_);
					
					
					if(is_null($XPATH_EMAIL_GLOBAL_) == false || $XPATH_EMAIL_GLOBAL_ != 'false'){
					 $sql_contact = "SELECT * FROM dbo.shipment_contacts WHERE dbo.shipment_contacts.shipment_id = '$ship_idlast' AND dbo.shipment_contacts.address_type ='$PATH_ADDRESSTYPE_' AND dbo.shipment_contacts.organization_code = '$XPATH_ORGCODE_GLOBAL' AND email='$XPATH_EMAIL_GLOBAL_'";
					$qryResultContact = sqlsrv_query($conn, $sql_contact);
					$ifContactExist = sqlsrv_has_rows($qryResultContact);
					
					if($ifContactExist === false && strlen($XPATH_EMAIL_GLOBAL) > 5 && $PATH_ADDRESSTYPE_ == "ConsigneeDocumentaryAddress"){
					   $sql_Insert_contact = "INSERT INTO FROM dbo.shipment_contacts (shipment_id,address_type,organization_code,email,is_default,company_name)
					   VALUES ('".$ship_idlast."','".$PATH_ADDRESSTYPE_."','".$XPATH_ORGCODE_GLOBAL."','".$XPATH_EMAIL_GLOBAL_."','N','".$XPATH_COMPANY_."');";
					   $qry_Insert_contact = sqlsrv_query($conn, $sql_Insert_contact);
					}	
				}
							
			}
						
						
						
						
                        //INSERT RECORD FOR TBLCONTAINER
						if ($CONTAINERctr > 1) {
							for ($c = 0; $c <= $CONTAINERctr - 1; $c++) {
								$XPATH_CONTAINERNUMBER = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ContainerNumber");
								$CONTAINERNUMBER = $parser->encode($XPATH_CONTAINERNUMBER);
								$CONTAINERNUMBER = getArrayName($CONTAINERNUMBER);
								$XPATH_CONTAINERTYPE = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ContainerType.Code");
								$CONTAINERTYPE = $parser->encode($XPATH_CONTAINERTYPE);
								$CONTAINERTYPE = getArrayName($CONTAINERTYPE);
								$XPATH_DELIVERYMODE = jsonPath($universal_shipment, $path_ContainerCollection."[$c].DeliveryMode");
								$DELIVERYMODE = $parser->encode($XPATH_DELIVERYMODE);
								$DELIVERYMODE = getArrayName($DELIVERYMODE);
								$XPATH_CATEGORYDESCRIPTION = jsonPath(
									$universal_shipment,
									"$.Body.UniversalShipment.Shipment.ContainerCollection.Container[$c].ContainerType.Category.Description"
								);
								$CATEGORYDESCRIPTION = $parser->encode($XPATH_CATEGORYDESCRIPTION);
								$CATEGORYDESCRIPTION = getArrayName($CATEGORYDESCRIPTION);
								$sqlInsertRecord_Container = "INSERT INTO shipcontainer
								(shipment_id,containershipnumber, containernumber, containertype, containerdeliverymode, containerdescription)
								Values($ship_idlast,'" . $SHIPMENTKEY . "','" . $CONTAINERNUMBER . "','" . $CONTAINERTYPE . "','" . $DELIVERYMODE . "','" . $CATEGORYDESCRIPTION . "')";
								$insertRecContainer = sqlsrv_query($conn, $sqlInsertRecord_Container);
                                //$sqlInsertRecord_Container;

							}
						} elseif ($CONTAINERctr == 1) {
							for ($c = 1; $c <= $CONTAINERctr; $c++) {
								$XPATH_CONTAINERNUMBER = jsonPath($universal_shipment, $path_ContainerCollection.".ContainerNumber");
								$CONTAINERNUMBER = $parser->encode($XPATH_CONTAINERNUMBER);
								$CONTAINERNUMBER = getArrayName($CONTAINERNUMBER);
								$XPATH_CONTAINERTYPE = jsonPath($universal_shipment, $path_ContainerCollection.".ContainerType.Code");
								$CONTAINERTYPE = $parser->encode($XPATH_CONTAINERTYPE);
								$CONTAINERTYPE = getArrayName($CONTAINERTYPE);
								$XPATH_DELIVERYMODE = jsonPath($universal_shipment, $path_ContainerCollection.".DeliveryMode");
								$DELIVERYMODE = $parser->encode($XPATH_DELIVERYMODE);
								$DELIVERYMODE = getArrayName($DELIVERYMODE);
								$XPATH_CATEGORYDESCRIPTION = jsonPath($universal_shipment,$path_ContainerCollection.".ContainerType.Category.Description");
								$CATEGORYDESCRIPTION = $parser->encode($XPATH_CATEGORYDESCRIPTION);
								$CATEGORYDESCRIPTION = getArrayName($CATEGORYDESCRIPTION);
								$sqlInsertRecord_Container = "INSERT INTO shipcontainer
								(shipment_id,containershipnumber, containernumber, containertype, containerdeliverymode, containerdescription)
								Values($ship_idlast,'" . $SHIPMENTKEY . "','" . $CONTAINERNUMBER . "','" . $CONTAINERTYPE . "','" . $DELIVERYMODE . "','" . $CATEGORYDESCRIPTION . "')";
								$insertRecContainer = sqlsrv_query($conn, $sqlInsertRecord_Container);
							}
						}
						$destination_path = "E:/A2BFREIGHT_MANAGER/$client_email/CW_SUCCESS/";
						process_shipment(
							$SHIPMENTKEY,
							$client_email,
							$ship_idlast,
							$webservicelink,
							$service_user,
							$service_password,
							$server_id,
							$enterprise_id,
							$auth,
							$company_code
						);
						//rename($filename, $destination_path . pathinfo($filename, PATHINFO_BASENAME));
					} else 
			{
				if ($TRANS_ETA == 'false' || $TRANS_ETD == 'false') {
							 $TRANS_ETA = null;
							 $TRANS_ETD = null;
						}
						while ($row_shipid = sqlsrv_fetch_array($qryResultShipID, SQLSRV_FETCH_ASSOC)) {
								$ship_id = $row_shipid['id'];
						}
						$sqlUpdateRecord = "Update shipment
				        Set console_id='$CONSOLNUMBER', master_bill ='$WAYBILLNUMBER', house_bill='$HOUSEWAYBILLNUMBER', transport_mode='$TRANSMODE',
				        vessel_name='$VESSELNAME', voyage_flight_num='$VOYAGEFLIGHTNO', vesslloyds='$VESSELLOYDSIMO', eta='$TRANS_ETA', etd='$TRANS_ETD', place_delivery='$PLACEOFDELIVERY', place_receipt='$PLACEOFRECEIPT',
				        consignee='$CONSIGNEE',consignor='$CONSIGNOR',sending_agent='$PATH_SENDINGAGENT',receiving_agent='$PATH_RECEIVINGAGENT',receiving_agent_addr='$RECEIVINGAGENTADDRESS',
				        sending_agent_addr='$SENDINGAGENTADDRESS',consignee_addr='$CONSIGNEEADDRESS',consignor_addr='$CONSIGNORADDRESS',trigger_date='$SHIP_TRIGGERDATE'
				        WHERE shipment_num = '$SHIPMENTKEY' AND user_id = '$CLIENT_ID'";
						$updateRec = sqlsrv_query($conn, $sqlUpdateRecord);
					
						while ($row_shipid = sqlsrv_fetch_array($qryResultShipID, SQLSRV_FETCH_ASSOC)) {
							$ship_idlast = $row_shipid['id'];
						}
						//rename($filename, $destination_path . pathinfo($filename, PATHINFO_BASENAME));
                        //UPDATE RECORD FOR NEW CONTAINER
						
				for ($a = 0; $a <= $OrganizationAddress_ctr - 1; $a++) {
					$XPATH_COMPANYNAME_ = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].OrganizationCode");
					$XPATH_ADDRESSTYPE = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].AddressType");
					$XPATH_EMAIL= jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].Email");
					$PATH_ADDRESSTYPE = $parser->encode($XPATH_ADDRESSTYPE);
					$PATH_ADDRESSTYPE = getArrayName($PATH_ADDRESSTYPE);
					$XPATH_EMAIL_GLOBAL = $parser->encode($XPATH_EMAIL);
					$XPATH_EMAIL_GLOBAL = getArrayName($XPATH_EMAIL_GLOBAL);
					$XPATH_ORGCODE_GLOBAL_ = $parser->encode($XPATH_COMPANYNAME_);
					$XPATH_ORGCODE_GLOBAL_ = getArrayName($XPATH_ORGCODE_GLOBAL_);
					$XPATH_COMPANY = jsonPath($universal_shipment,$path_SubUniversalSubShipment.".OrganizationAddress[$a].CompanyName");
					$XPATH_COMPANY = $parser->encode($XPATH_COMPANY);
					$XPATH_COMPANY = getArrayName($XPATH_COMPANY);
					
				
				if(is_null($XPATH_EMAIL_GLOBAL) == false || $XPATH_EMAIL_GLOBAL != 'false'){
					 $sql_contact_ = "SELECT * FROM dbo.shipment_contacts WHERE dbo.shipment_contacts.shipment_id = '$ship_id' AND dbo.shipment_contacts.address_type ='$PATH_ADDRESSTYPE' AND dbo.shipment_contacts.organization_code = '$XPATH_ORGCODE_GLOBAL_' AND email='$XPATH_EMAIL_GLOBAL'";
					$qryResultContact = sqlsrv_query($conn, $sql_contact_);
					$ifContactExist = sqlsrv_has_rows($qryResultContact);
					
					if($ifContactExist === false && strlen($XPATH_EMAIL_GLOBAL) > 5 && $PATH_ADDRESSTYPE == "ConsigneeDocumentaryAddress"){
					   $sql_Insert_contact_ = "INSERT INTO dbo.shipment_contacts (shipment_id,address_type,organization_code,email,is_default,company_name)
					   VALUES ('".$ship_id."','".$PATH_ADDRESSTYPE."','".$XPATH_ORGCODE_GLOBAL_."','".$XPATH_EMAIL_GLOBAL."','N','".$XPATH_COMPANY."');";
					   $sql_Insert_contact_ = sqlsrv_query($conn, $sql_Insert_contact_);
					}
				}
			}
						
				for ($b = 0; $b <= $OrganizationAddress_ctr1 - 1; $b++) {
						$XPATH_COMPANYNAME_ = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$a].OrganizationCode");
						$ADDRESSTYPE_ = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].AddressType");
						$EMAIL_ = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$b].Email");
						$XPATH_EMAIL_GLOBAL_ = $parser->encode($EMAIL_);
						$XPATH_EMAIL_GLOBAL_ = getArrayName($XPATH_EMAIL_GLOBAL_);
						$XPATH_ORGCODE_GLOBAL_ = $parser->encode($XPATH_COMPANYNAME_);
						$XPATH_ORGCODE_GLOBAL_ = getArrayName($XPATH_ORGCODE_GLOBAL_);
						$PATH_ADDRESSTYPE_ = $parser->encode($ADDRESSTYPE_);
					    $PATH_ADDRESSTYPE_ = getArrayName($PATH_ADDRESSTYPE_);
						$XPATH_COMPANY_ = jsonPath($universal_shipment,$path_AddressUniversalShipment.".OrganizationAddress[$a].CompanyName");
						$XPATH_COMPANY_ = $parser->encode($XPATH_COMPANY_);
						$XPATH_COMPANY_ = getArrayName($XPATH_COMPANY_);
						
					
					if(is_null($XPATH_EMAIL_GLOBAL_) == false || $XPATH_EMAIL_GLOBAL_ != 'false'){
					 $sql_contact = "SELECT * FROM dbo.shipment_contacts WHERE dbo.shipment_contacts.shipment_id = '$ship_id' AND dbo.shipment_contacts.address_type ='$PATH_ADDRESSTYPE_' AND dbo.shipment_contacts.organization_code = '$XPATH_ORGCODE_GLOBAL_' AND email='$XPATH_EMAIL_GLOBAL_'";
					$qryResultContact = sqlsrv_query($conn, $sql_contact);
					$ifContactExist = sqlsrv_has_rows($qryResultContact);

				if($ifContactExist === false && strlen($XPATH_EMAIL_GLOBAL) > 5 && $PATH_ADDRESSTYPE_ == "ConsigneeDocumentaryAddress"){
					   $sql_Insert_contact = "INSERT INTO dbo.shipment_contacts (shipment_id,address_type,organization_code,email,is_default,company_name)
					   VALUES ('".$ship_id."','".$PATH_ADDRESSTYPE."','".$XPATH_ORGCODE_GLOBAL_."','".$XPATH_EMAIL_GLOBAL_."','N','".$XPATH_COMPANY_."');";
					   //$qry_Insert_contact = sqlsrv_query($conn, $sql_Insert_contact);
					}	
				}
			}
		
						if ($CONTAINERctr > 1) {
							for ($c = 0; $c <= $CONTAINERctr - 1; $c++) {
								$XPATH_CONTAINERNUMBER = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ContainerNumber");
								$CONTAINERNUMBER = $parser->encode($XPATH_CONTAINERNUMBER);
								$CONTAINERNUMBER = getArrayName($CONTAINERNUMBER);
								$XPATH_CONTAINERTYPE = jsonPath($universal_shipment, $path_ContainerCollection."[$c].ContainerType.Code");
								$CONTAINERTYPE = $parser->encode($XPATH_CONTAINERTYPE);
								$CONTAINERTYPE = getArrayName($CONTAINERTYPE);
								$XPATH_DELIVERYMODE = jsonPath($universal_shipment, $path_ContainerCollection."[$c].DeliveryMode");
								$DELIVERYMODE = $parser->encode($XPATH_DELIVERYMODE);
								$DELIVERYMODE = getArrayName($DELIVERYMODE);
								$XPATH_CATEGORYDESCRIPTION = jsonPath($universal_shipment,$path_ContainerCollection."[$c].ContainerType.Category.Description");
								$CATEGORYDESCRIPTION = $parser->encode($XPATH_CATEGORYDESCRIPTION);
								$CATEGORYDESCRIPTION = getArrayName($CATEGORYDESCRIPTION);
								
								
								$sqlSearchContainer = "Select * from shipcontainer WHERE
								WHERE shipment_id='$ship_id' AND containernumber='$CONTAINERNUMBER'";
								$searchContainer = sqlsrv_query($conn, $sqlSearchContainer);
								$ifContainerIDExist = sqlsrv_has_rows($searchContainer);
								
								if($ifContainerIDExist == true)
							    {
								$sqlInsertRecord_Container = "Update shipcontainer SET
								containernumber='$CONTAINERNUMBER', containertype='$CONTAINERTYPE', containerdeliverymode='$DELIVERYMODE', containerdescription='$CATEGORYDESCRIPTION'
								WHERE shipment_id='$ship_id' AND containernumber='$CONTAINERNUMBER'";
								$insertRecContainer = sqlsrv_query($conn, $sqlInsertRecord_Container); 
								}
								else{
								$sqlInsertRecord_Container = "INSERT INTO shipcontainer
								(shipment_id,containershipnumber, containernumber, containertype, containerdeliverymode, containerdescription)
								Values($ship_id,'" . $SHIPMENTKEY . "','" . $CONTAINERNUMBER . "','" . $CONTAINERTYPE . "','" . $DELIVERYMODE . "','" . $CATEGORYDESCRIPTION . "')";
								$insertRecContainer = sqlsrv_query($conn, $sqlInsertRecord_Container);	
								}        
							}	
						} 
						
						elseif ($CONTAINERctr == 1) {
							for ($c = 1; $c <= $CONTAINERctr; $c++) {
								$XPATH_CONTAINERNUMBER = jsonPath($universal_shipment, $path_ContainerCollection.".ContainerNumber");
								$CONTAINERNUMBER = $parser->encode($XPATH_CONTAINERNUMBER);
								$CONTAINERNUMBER = getArrayName($CONTAINERNUMBER);
								$XPATH_CONTAINERTYPE = jsonPath($universal_shipment, $path_ContainerCollection.".ContainerType.Code");
								$CONTAINERTYPE = $parser->encode($XPATH_CONTAINERTYPE);
								$CONTAINERTYPE = getArrayName($CONTAINERTYPE);
								$XPATH_DELIVERYMODE = jsonPath($universal_shipment, $path_ContainerCollection.".DeliveryMode");
								$DELIVERYMODE = $parser->encode($XPATH_DELIVERYMODE);
								$DELIVERYMODE = getArrayName($DELIVERYMODE);
								$XPATH_CATEGORYDESCRIPTION = jsonPath($universal_shipment,$path_ContainerCollection.".ContainerType.Category.Description");
								$CATEGORYDESCRIPTION = $parser->encode($XPATH_CATEGORYDESCRIPTION);
								$CATEGORYDESCRIPTION = getArrayName($CATEGORYDESCRIPTION);
								
								$sqlSearchContainer = "Select * from shipcontainer WHERE
								WHERE shipment_id='$ship_id' AND containernumber='$CONTAINERNUMBER'";
								$searchContainer = sqlsrv_query($conn, $sqlSearchContainer);
								$ifContainerIDExist = sqlsrv_has_rows($searchContainer);
								
								if($ifContainerIDExist == true)
							    {
								$sqlInsertRecord_Container = "Update shipcontainer SET
								containernumber='$CONTAINERNUMBER', containertype='$CONTAINERTYPE', containerdeliverymode='$DELIVERYMODE', containerdescription='$CATEGORYDESCRIPTION'
								WHERE shipment_id='$ship_id' AND containernumber='$CONTAINERNUMBER'";
								$insertRecContainer = sqlsrv_query($conn, $sqlInsertRecord_Container); 
								}
								else{
								$sqlInsertRecord_Container = "INSERT INTO shipcontainer
								(shipment_id,containershipnumber, containernumber, containertype, containerdeliverymode, containerdescription)
								Values($ship_id,'" . $SHIPMENTKEY . "','" . $CONTAINERNUMBER . "','" . $CONTAINERTYPE . "','" . $DELIVERYMODE . "','" . $CATEGORYDESCRIPTION . "')";
								$insertRecContainer = sqlsrv_query($conn, $sqlInsertRecord_Container);	
								}  
							}
						}
						

						process_shipment(
							$SHIPMENTKEY,
							$client_email,
							$ship_id,
							$webservicelink,
							$service_user,
							$service_password,
							$server_id,
							$enterprise_id,
							$auth,
							$company_code
						);
						rename($filename, $destination_path . pathinfo($filename, PATHINFO_BASENAME));
					}
				}
                //END OF DATA MANAGEMENT

			}
		}
	}
} else {
	die("eAdaptor not found");
	
}
echo "Run Script Success";
