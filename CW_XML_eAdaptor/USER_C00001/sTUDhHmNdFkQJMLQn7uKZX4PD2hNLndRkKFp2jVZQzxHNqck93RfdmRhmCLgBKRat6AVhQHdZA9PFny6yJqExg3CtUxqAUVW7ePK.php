 <?php
require_once( 'json.php' );
require_once( 'jsonpath-0.8.1.php' );
require_once( 'connection.php' );
set_time_limit( 0 );
$CLIENT_ID       = "C00001";
$CLIENT_XML_PATH = "";
header( 'Content-Type: text/plain' );

function returnShipKey( $key, $CLIENT_ID )
{
$serverName = "a2bserver.database.windows.net"; 
$connectionInfo = array( "Database"=>"a2bfreighthub_db", "UID"=>"A2B_Admin", "PWD"=>"v9jn9cQ9dF7W");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     //echo "Connection established.<br />";
}else{
    // echo "Connection could not be established";
     die( print_r( sqlsrv_errors(), true));
}
	
    $curl_ = curl_init();
    curl_setopt_array( $curl_, array(
         CURLOPT_URL => "https://a2bprdservices.wisegrid.net/eAdaptor",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "<UniversalDocumentRequest xmlns=\"http://www.cargowise.com/Schemas/Universal/2011/11\" version=\"1.1\">\r\n
        <DocumentRequest>\r\n
        <DataContext>\r\n
        <DataTargetCollection>\r\n
        <DataTarget>\r\n
        <Type>ForwardingShipment</Type>\r\n
        <Key>$key</Key>\r\n
        </DataTarget>\r\n
        </DataTargetCollection>\r\n
        <Company>\r\n
        <Code>SYD</Code>\r\n
        </Company>\r\n
        <EnterpriseID>A2B</EnterpriseID>\r\n
        <ServerID>PRD</ServerID>\r\n
        </DataContext>\r\n
        </DocumentRequest>\r\n
        </UniversalDocumentRequest>",
        CURLOPT_HTTPHEADER => array(
             "Authorization: Basic QTJCOkh3N20zWGhT",
            "Content-Type: application/xml",
            "Cookie: WEBSVC=f50e2886473c750f" 
        ) 
    ) );
    $parser = new Services_JSON( SERVICES_JSON_LOOSE_TYPE );
    curl_setopt( $curl_, CURLOPT_SSL_VERIFYPEER, false );
    $document_request = curl_exec( $curl_ );
    curl_close( $curl_ );
    $xml_docs             = simplexml_load_string( $document_request );
    $json_documentrequest = json_encode( $xml_docs, JSON_PRETTY_PRINT );
    $json_xpathdoc        = json_decode( $json_documentrequest, true );
    $ctr_file_1           = 1;
    $ctr_file_2           = 0;
	
    for ( $x = 0; $x <= 1; $x++ )
    {
        $xpath_AttachedCountSingle = jsonPath( $json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument.FileName" );
        $xpath_AttachedB64         = jsonPath( $json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument.ImageData" );
        $xpath_DocType             = jsonPath( $json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument.Type.Code" );
		$xpath_SavedUtc            = jsonPath( $json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument.SaveDateUTC" );
		$xpath_SavedBy             = jsonPath( $json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument.SavedBy.Code" );
		$xpath_SavedEventTime      = jsonPath( $json_xpathdoc, "$.Data.UniversalEvent.Event.EventTime" );
		
        $SingleAttach_ctr          = $parser->encode( $xpath_AttachedCountSingle );
        $SingleAttach_ctrb64       = $parser->encode( $xpath_AttachedB64 );
        $DocType                   = $parser->encode( $xpath_DocType );
		$Saved_date                = $parser->encode( $xpath_SavedUtc );
		$Saved_EventTime           = $parser->encode( $xpath_SavedEventTime );
		$Saved_By              	   = $parser->encode( $xpath_SavedBy );
        $ctr_1                     = getArrayName( $SingleAttach_ctr );
        $ctr_b64                   = getArrayName( $SingleAttach_ctrb64 );
        $get_valDocType_           = getArrayName( $DocType );		
		$get_valSavedDate          = getArrayName( $Saved_date );
		$get_Saved_By              = getArrayName( $Saved_By );
		$get_Saved_EventTime       = getArrayName( $Saved_EventTime );
		
        if ( strval( $SingleAttach_ctr ) == 'false' )
        {
            $ctr_file_1 = 0;
            break;
        }
        else
        {
            $ctr_file_1++;
            $ctr_1;
			$sqlSearchRecord = "SELECT
			dbo.tblCW1_ShipmentDoc.cw_DocumentName
			FROM
			dbo.tblCW1_ShipmentDoc
			WHERE
			dbo.tblCW1_ShipmentDoc.cw_DocumentName = '$ctr_1'";
			$execRecord = sqlsrv_query( $conn, $sqlSearchRecord );
			$ifDocumentExist    = sqlsrv_has_rows( $execRecord );
			
			if ( $ifDocumentExist === true ){
				$sqlUpdateRecord ="UPDATE tblCW1_ShipmentDoc
				SET cw_DocumentType = '$get_valDocType_', cw_DocumentSavedBy = '$get_Saved_By', cw_DocumentSaveDate = '$get_valSavedDate', cw_DocumentEventDate ='$get_Saved_EventTime', cw_ImageData ='$ctr_b64'
				WHERE cw_DocumentName='$ctr_1'";
				$execRecord = sqlsrv_query( $conn, $sqlUpdateRecord );
				break;
			}
			elseif( $ifDocumentExist === false ){
			$sqlInsertRecord = "INSERT INTO tblCW1_ShipmentDoc
            (cw_DocumentShipmentNumber, cw_DocumentType, cw_DocumentName, cw_DocumentSavedBy, cw_DocumentSaveDate, cw_DocumentEventDate, cw_ImageData ) Values
			('".$key."','".$get_valDocType_."','".$ctr_1."','".$get_Saved_By."','".$get_valSavedDate."','".$get_Saved_EventTime."','".$ctr_b64."')";
			$execRecord = sqlsrv_query( $conn, $sqlInsertRecord );
			
            Base64_Decoder( $ctr_b64, $ctr_1, $CLIENT_ID, $get_valDocType_, $key );
				
			}
			
            
        }
    }
    if ( $ctr_file_1 == 0 )
    {
        for ( $b = 0; $b <= 100; $b++ )
        {
            $xpath_AttachedCountMany = jsonPath( $json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument[$b].FileName" );
            $xpath_AttachedCountb64  = jsonPath( $json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument[$b].ImageData" );
            $xpath_SavedUtc          = jsonPath( $json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument[$b].SaveDateUTC" );
            $xpath_DocType           = jsonPath( $json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument[$b].Type.Code" );
			$xpath_SavedBy           = jsonPath( $json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument[$b].SavedBy.Code" );
			$xpath_SavedEventTime    = jsonPath( $json_xpathdoc, "$.Data.UniversalEvent.Event.EventTime" );
            $MultipleAttach_ctr      = $parser->encode( $xpath_AttachedCountMany );
            $MultipleAttach_ctrb64   = $parser->encode( $xpath_AttachedCountb64 );
            $Saved_date              = $parser->encode( $xpath_SavedUtc );
            $DocType                 = $parser->encode( $xpath_DocType );
			$Saved_EventTime         = $parser->encode( $xpath_SavedEventTime );
			$Saved_By              	 = $parser->encode( $xpath_SavedBy );
            $ctr_2                   = getArrayName( $MultipleAttach_ctr );
            $ctr_2b64                = getArrayName( $MultipleAttach_ctrb64 );
            $get_valSavedDate        = getArrayName( $Saved_date );
            $get_valDocType          = getArrayName( $DocType );
			$get_Saved_EventTime     = getArrayName( $Saved_EventTime );
			$get_Saved_By     		 = getArrayName( $Saved_By );
            if ( $ctr_2 === 'false' )
            {
                break;
            }
            else
            {
            $ctr_file_2++;
            $ctr_2;
			
			$sqlSearchRecord = "SELECT
			dbo.tblCW1_ShipmentDoc.cw_DocumentName
			FROM
			dbo.tblCW1_ShipmentDoc
			WHERE
			dbo.tblCW1_ShipmentDoc.cw_DocumentName = '$ctr_2'";
			$execRecord = sqlsrv_query( $conn, $sqlSearchRecord );
			$ifDocumentExist    = sqlsrv_has_rows( $execRecord );
			
			if ( $ifDocumentExist === true ){
				
				$sqlUpdateRecord ="UPDATE tblCW1_ShipmentDoc
				SET cw_DocumentType = '$get_valDocType', cw_DocumentSavedBy = '$get_Saved_By', cw_DocumentSaveDate = '$get_valSavedDate', cw_DocumentEventDate ='$get_Saved_EventTime', cw_ImageData ='$ctr_2b64'
				WHERE cw_DocumentName='$ctr_2'";
				$execRecord = sqlsrv_query( $conn, $sqlUpdateRecord );
				Base64_Decoder( $ctr_2b64, $ctr_2, $CLIENT_ID, $get_valDocType, $key );
		  
			}
			elseif($ifDocumentExist === false ){
			$sqlInsertRecord = "INSERT INTO tblCW1_ShipmentDoc
            (cw_DocumentShipmentNumber, cw_DocumentType, cw_DocumentName, cw_DocumentSavedBy, cw_DocumentSaveDate, cw_DocumentEventDate, cw_ImageData ) Values
			('".$key."','".$get_valDocType."','".$ctr_2."','".$get_Saved_By."','".$get_valSavedDate."','".$get_Saved_EventTime."','".$ctr_2b64."')";
			$execRecord = sqlsrv_query( $conn, $sqlInsertRecord );		
            Base64_Decoder( $ctr_2b64, $ctr_2, $CLIENT_ID, $get_valDocType, $key );
				
			}
			
			
            }
        }
    }
}
function getArrayName( $val )
{
    return str_replace( array(
        '["',
        '"]',
        '[',
        ']' 
    ), array(
        "",
        "",
        "",
        "" 
    ), $val );
}
function Base64_Decoder( $val, $valName, $id, $pathName, $shipkey )
{
    $path = "E:/A2BFREIGHT_MANAGER/CLIENT_$id/CW_FILE/$shipkey/$pathName/";
    $b64  = str_replace( array(
        '["',
        '"]' 
    ), array(
        "",
        "" 
    ), $val );
    if ( !is_dir( $path ) )
    {
        mkdir( $path, 0777, true );
        file_put_contents( $path . $valName, base64_decode( $b64 ) );
    }
    else
    {
        file_put_contents( $path . $valName, base64_decode( $b64 ) );
    }
}

foreach ( glob( "E:/A2BFREIGHT_MANAGER/CLIENT_$CLIENT_ID/CW_XML/*" ) as $filename )
{
    $parser             = new Services_JSON( SERVICES_JSON_LOOSE_TYPE );
    $myxmlfilecontent   = file_get_contents( $filename );
    $xml                = simplexml_load_string( $myxmlfilecontent );
    $universalshipment  = json_encode( $xml, JSON_PRETTY_PRINT );
    $universal_shipment = json_decode( $universalshipment, true );
    
    $XPATH_SHIPMENTKEY = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.DataContext.DataSourceCollection.DataSource[1].Key" );
    $SHIPMENTKEY       = $parser->encode( $XPATH_SHIPMENTKEY );
    $SHIPMENTKEY       = getArrayName( $SHIPMENTKEY );
    
    $XPATH_CONSOLNUMBER = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.DataContext.DataSourceCollection.DataSource[0].Key" );
    $CONSOLNUMBER       = $parser->encode( $XPATH_CONSOLNUMBER );
    $CONSOLNUMBER       = getArrayName( $CONSOLNUMBER );
    
    //GET WAYBLL NUMBER
    $XPATH_WAYBILLNUMBER = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.WayBillNumber" );
    $WAYBILLNUMBER       = $parser->encode( $XPATH_WAYBILLNUMBER );
    $WAYBILLNUMBER       = getArrayName( $WAYBILLNUMBER );
    
    //GET HOUSEBILL NUMBER
    $XPATH_HOUSEWAYBILLNUMBER = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.WayBillNumber" );
    $HOUSEWAYBILLNUMBER       = $parser->encode( $XPATH_HOUSEWAYBILLNUMBER );
    $HOUSEWAYBILLNUMBER       = getArrayName( $HOUSEWAYBILLNUMBER );
    
    //GET TRANSPORT MODE
    $XPATH_TRANSMODE = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.TransportLegCollection.TransportLeg.TransportMode" );
    $TRANSMODE       = $parser->encode( $XPATH_TRANSMODE );
    $TRANSMODE       = getArrayName( $TRANSMODE );
    
    $XPATH_SHIP_ETD = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.TransportLegCollection.TransportLeg.EstimatedDeparture" );
    $TRANS_ETD      = $parser->encode( $XPATH_SHIP_ETD );
    $TRANS_ETD      = getArrayName( $TRANS_ETD );
    
    $XPATH_SHIP_ETA = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.TransportLegCollection.TransportLeg.EstimatedArrival" );
    $TRANS_ETA      = $parser->encode( $XPATH_SHIP_ETA );
    $TRANS_ETA      = getArrayName( $TRANS_ETA );
    
    //GET VESSEL NAME
    $XPATH_VESSELNAME = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.VesselName" );
    $VESSELNAME       = $parser->encode( $XPATH_VESSELNAME );
    $VESSELNAME       = getArrayName( $VESSELNAME );
    
    //GET VOYAGE#
    $XPATH_VOYAGEFLIGHTNO = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.VoyageFlightNo" );
    $VOYAGEFLIGHTNO       = $parser->encode( $XPATH_VOYAGEFLIGHTNO );
    $VOYAGEFLIGHTNO       = getArrayName( $VOYAGEFLIGHTNO );
    
    //GET VESSELLOYDSIMO
    $XPATH_VESSELLOYDSIMO = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.TransportLegCollection.TransportLeg.VesselLloydsIMO" );
    $VESSELLOYDSIMO       = $parser->encode( $XPATH_VESSELLOYDSIMO );
    $VESSELLOYDSIMO       = getArrayName( $VESSELLOYDSIMO );
    
    //GET PLACEOFDELIVERY
    $XPATH_PLACEOFDELIVERY = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.PlaceOfDelivery.Name" );
    $PLACEOFDELIVERY       = $parser->encode( $XPATH_PLACEOFDELIVERY );
    $PLACEOFDELIVERY       = getArrayName( $PLACEOFDELIVERY );
    
    //GET PLACEOFRECEIPT
    $XPATH_PLACEOFRECEIPT = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.PlaceOfReceipt.Name" );
    $PLACEOFRECEIPT       = $parser->encode( $XPATH_PLACEOFRECEIPT );
    $PLACEOFRECEIPT       = getArrayName( $PLACEOFRECEIPT );
    
    //GET CONTAINER COUNT
    $XPATH_CONTAINERCOUNT = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.ContainerCount" );
    $CONTAINERCOUNT       = $parser->encode( $XPATH_CONTAINERCOUNT );
    $CONTAINERctr         = getArrayName( $CONTAINERCOUNT );
    $CONTAINERctr         = (int) $CONTAINERctr;
	
	$OrganizationAddress = jsonPath($universal_shipment, "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress");
	$OrganizationAddress_ctr = $OrganizationAddress;
	
	if($OrganizationAddress_ctr != false){
	$OrganizationAddress = jsonPath($universal_shipment, "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress");
	$OrganizationAddress_ctr = count($OrganizationAddress[0]);
	}
	else{
	$OrganizationAddress_ctr = 0;
	}
	
	
	for($a=0;$a<= $OrganizationAddress_ctr - 1;$a++){
	
	$XPATH_ORGANIZATIONCODE= jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].OrganizationCode" );
	$XPATH_ADDRESS1= jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].Address1" );
	$XPATH_ADDRESS2= jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].Address2" );
	$XPATH_ADDRESSCODE= jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].AddressShortCode" );
	$XPATH_COMPANYNAME= jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].CompanyName" );
	$XPATH_PORTNAME= jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].Port.Name" );
	$XPATH_STATE= jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].State" );
	$XPATH_POSTCODE = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].Postcode" );
	$XPATH_COUNTRY = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].Country.Name" );
	$XPATH_ADDRESSTYPE = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].AddressType" );
   
    $PATH_ADDRESSTYPE       = $parser->encode( $XPATH_ADDRESSTYPE );
	$PATH_ADDRESSTYPE       = getArrayName( $PATH_ADDRESSTYPE );
   
   if($PATH_ADDRESSTYPE == "SendersOverseasAgent"){
	   $XPATH_ORGANIZATIONCODE       = $parser->encode($XPATH_ORGANIZATIONCODE);
	   $PATH_SENDINGAGENT       = getArrayName($XPATH_ORGANIZATIONCODE);
	   
	   $XPATH_ADDRESS1       = $parser->encode($XPATH_ADDRESS1);
	   $PATH_SENDINGAGENTADDRESS1       = getArrayName($XPATH_ADDRESS1); 
	   
	   $XPATH_ADDRESS2       = $parser->encode($XPATH_ADDRESS2);
	   $PATH_SENDINGAGENTADDRESS2       = getArrayName($XPATH_ADDRESS2);
	   
	   $XPATH_ADDRESSCODE       = $parser->encode($XPATH_ADDRESSCODE);
	   $PATH_SENDINGAGENTADDRESSCODE       = getArrayName($XPATH_ADDRESSCODE);
	   
	   $XPATH_PORTNAME       = $parser->encode($XPATH_PORTNAME);
	   $PATH_SENDINGAGENTPORTNAME       = getArrayName($XPATH_PORTNAME);
	   
	   $XPATH_STATE       = $parser->encode($XPATH_STATE);
	   $PATH_SENDINGAGENTSTATE       = getArrayName($XPATH_STATE);
	   
	   $SENDINGAGENTADDRESS = $PATH_SENDINGAGENTADDRESS1. ", " .$PATH_SENDINGAGENTADDRESSCODE. ", ".$PATH_SENDINGAGENTADDRESS2.", ".$PATH_SENDINGAGENTPORTNAME.", ".$PATH_SENDINGAGENTSTATE;
	   
   }
   if($PATH_ADDRESSTYPE == "ConsigneeDocumentaryAddress"){
	   $XPATH_COMPANYNAME       = $parser->encode($XPATH_COMPANYNAME);
	   $PATH_CONSIGNEE       = getArrayName($XPATH_COMPANYNAME);
	   
	   $XPATH_ADDRESS1       = $parser->encode($XPATH_ADDRESS1);
	   $PATH_CONSIGNEEADDRESS1       = getArrayName($XPATH_ADDRESS1); 
	   
	   $XPATH_STATE       = $parser->encode($XPATH_STATE);
	   $PATH_CONSIGNEESTATE       = getArrayName($XPATH_STATE);
	   
	   $XPATH_POSTCODE       = $parser->encode($XPATH_POSTCODE);
	   $PATH_CONSIGNEEPOSTCODE       = getArrayName($XPATH_POSTCODE);
	   
	   $XPATH_COUNTRY       = $parser->encode($XPATH_COUNTRY);
	   $PATH_CONSIGNEECOUNTRY       = getArrayName($XPATH_COUNTRY);
	   
	   $CONSIGNEEADDRESS = $PATH_CONSIGNEEADDRESS1. ", " .$PATH_CONSIGNEESTATE. ", ".$PATH_CONSIGNEEPOSTCODE.", ".$PATH_CONSIGNEECOUNTRY;
   }
   if($PATH_ADDRESSTYPE == "ConsignorPickupDeliveryAddress"){
	   $XPATH_COMPANYNAME       = $parser->encode($XPATH_COMPANYNAME);
	   $PATH_CONSIGNORCOMPANY       = getArrayName($XPATH_COMPANYNAME);
	   
	   $XPATH_ADDRESS1       = $parser->encode($XPATH_ADDRESS1);
	   $PATH_CONSIGNORADDRESS       = getArrayName($XPATH_ADDRESS1); 
   }
   if($PATH_ADDRESSTYPE == "DeliveryAgent"){
	   $XPATH_COMPANYNAME       = $parser->encode($XPATH_COMPANYNAME);
	   $PATH_RECEIVINGAGENT       = getArrayName($XPATH_COMPANYNAME); 
	   
	   $XPATH_ADDRESS1       = $parser->encode($XPATH_ADDRESS1);
	   $PATH_RECEIVINGAGENTADDRESS1       = getArrayName($XPATH_ADDRESS1);
   }
 
}

	
       
    //START OF DATA SQL MANAGEMENT
    if (!empty( $SHIPMENTKEY ) || $SHIPMENTKEY <> "" )
    {
				$sql= "SELECT
                dbo.tblCW1_Shipment.cw_ShipmentNumber
                FROM
                dbo.tblCW1_Shipment
                WHERE
                dbo.tblCW1_Shipment.cw_ShipmentNumber = '$SHIPMENTKEY'";
				$qryResultShipID  = sqlsrv_query( $conn, $sql );
				$ifShipIDExist    = sqlsrv_has_rows( $qryResultShipID );
				$destination_path = "E:/A2BFREIGHT_MANAGER/CLIENT_$CLIENT_ID/CW_SUCCESS/";
        
        if ( $ifShipIDExist === false )
        {
				$sqlInsertRecord = "INSERT INTO tblCW1_Shipment
                (cw_ConsolNumber, cw_ShipmentNumber, cw_MasterBill, cw_HouseBill, cw_TransportMode,
                cw_VesselName, cw_VoyageFlightNo, cw_Lloyd, cw_ETA, cw_ETD, cw_PlaceOfDelivery, cw_PlaceOfReceipt,
				cw_Consignee, cw_Consignor, cw_SendingAgent, cw_ReceivingAgent, cw_ReceivingAgentAddress, cw_SendingAgentAddress, cw_ConsigneeAddress, cw_ConsignorAddress)
                Values('" . $CONSOLNUMBER . "','" . $SHIPMENTKEY . "','" . $WAYBILLNUMBER . "','" . $HOUSEWAYBILLNUMBER . "','" . $TRANSMODE . "','" . $VESSELNAME . "','" . $VOYAGEFLIGHTNO . "','" . $VESSELLOYDSIMO . "','" . $TRANS_ETA . "','" . $TRANS_ETD . "','" . $PLACEOFDELIVERY . "','" . $PLACEOFRECEIPT . "',
				'$PATH_CONSIGNEE','$PATH_CONSIGNORCOMPANY','$PATH_SENDINGAGENT','$PATH_RECEIVINGAGENT','$PATH_RECEIVINGAGENTADDRESS1','".$SENDINGAGENTADDRESS."','$CONSIGNEEADDRESS','$PATH_CONSIGNORADDRESS')";
				$insertRec = sqlsrv_query( $conn, $sqlInsertRecord );
            
            //INSERT RECORD FOR TBLCONTAINER
            for ( $c = 0; $c <= $CONTAINERctr - 1; $c++ )
            {
                $XPATH_CONTAINERNUMBER = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.ContainerCollection.Container[$c].ContainerNumber" );
                $CONTAINERNUMBER       = $parser->encode( $XPATH_CONTAINERNUMBER );
                $CONTAINERNUMBER       = getArrayName( $CONTAINERNUMBER );
                
                $XPATH_CONTAINERTYPE = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.ContainerCollection.Container[$c].ContainerType.Code" );
                $CONTAINERTYPE       = $parser->encode( $XPATH_CONTAINERTYPE );
                $CONTAINERTYPE       = getArrayName( $CONTAINERTYPE );
                
                $XPATH_DELIVERYMODE = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.ContainerCollection.Container[$c].DeliveryMode" );
                $DELIVERYMODE       = $parser->encode( $XPATH_DELIVERYMODE );
                $DELIVERYMODE       = getArrayName( $DELIVERYMODE );
                
                $XPATH_CATEGORYDESCRIPTION = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.ContainerCollection.Container[$c].ContainerType.Category.Description" );
                $CATEGORYDESCRIPTION       = $parser->encode( $XPATH_CATEGORYDESCRIPTION );
                $CATEGORYDESCRIPTION       = getArrayName( $CATEGORYDESCRIPTION );
                
                $sqlInsertRecord_Container = "INSERT INTO tblCW1_ShipmentContainer
                (cw_ContainerShipNumber, cw_ContainerNumber, cw_ContainerType, cw_ContainerDeliveryMode, cw_ContainerDescription)
                Values('" . $SHIPMENTKEY . "','" . $CONTAINERNUMBER . "','" . $CONTAINERTYPE . "','" . $DELIVERYMODE . "','" . $CATEGORYDESCRIPTION . "')";
                $insertRecContainer        = sqlsrv_query( $conn, $sqlInsertRecord_Container );
                //$sqlInsertRecord_Container;
                
                
            }
            returnShipKey( $SHIPMENTKEY, $CLIENT_ID );
            rename( $filename, $destination_path . pathinfo( $filename, PATHINFO_BASENAME ) );
        }
        
        else
        {
				$sqlUpdateRecord = "Update tblCW1_Shipment
				Set cw_MasterBill ='$WAYBILLNUMBER', cw_HouseBill='$HOUSEWAYBILLNUMBER', cw_TransportMode='$TRANSMODE',
				cw_VesselName='$VESSELNAME', cw_VoyageFlightNo='$VOYAGEFLIGHTNO', cw_Lloyd='$VESSELLOYDSIMO', cw_ETA='$TRANS_ETA', cw_ETD='$TRANS_ETD', cw_PlaceOfDelivery='$PLACEOFDELIVERY', cw_PlaceOfReceipt='$PLACEOFRECEIPT',
				cw_Consignee='$PATH_CONSIGNEE',cw_Consignor='$PATH_CONSIGNORCOMPANY',cw_SendingAgent='$PATH_SENDINGAGENT',cw_ReceivingAgent='$PATH_RECEIVINGAGENT',cw_ReceivingAgentAddress='$PATH_RECEIVINGAGENTADDRESS1',cw_SendingAgentAddress='$SENDINGAGENTADDRESS',cw_ConsigneeAddress='$CONSIGNEEADDRESS',cw_ConsignorAddress='$PATH_CONSIGNORADDRESS'
				WHERE cw_ShipmentNumber = '$SHIPMENTKEY'";
				$updateRec = sqlsrv_query( $conn, $sqlUpdateRecord );
            
		
			//INSERT RECORD FOR new TBLCONTAINER
            for ( $c = 0; $c <= $CONTAINERctr - 1; $c++ )
            {
                $XPATH_CONTAINERNUMBER = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.ContainerCollection.Container[$c].ContainerNumber" );
                $CONTAINERNUMBER       = $parser->encode( $XPATH_CONTAINERNUMBER );
                $CONTAINERNUMBER       = getArrayName( $CONTAINERNUMBER );
                
                $XPATH_CONTAINERTYPE = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.ContainerCollection.Container[$c].ContainerType.Code" );
                $CONTAINERTYPE       = $parser->encode( $XPATH_CONTAINERTYPE );
                $CONTAINERTYPE       = getArrayName( $CONTAINERTYPE );
                
                $XPATH_DELIVERYMODE = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.ContainerCollection.Container[$c].DeliveryMode" );
                $DELIVERYMODE       = $parser->encode( $XPATH_DELIVERYMODE );
                $DELIVERYMODE       = getArrayName( $DELIVERYMODE );
                
                $XPATH_CATEGORYDESCRIPTION = jsonPath( $universal_shipment, "$.Body.UniversalShipment.Shipment.ContainerCollection.Container[$c].ContainerType.Category.Description" );
                $CATEGORYDESCRIPTION       = $parser->encode( $XPATH_CATEGORYDESCRIPTION );
                $CATEGORYDESCRIPTION       = getArrayName( $CATEGORYDESCRIPTION );

				
				$sqlUpdateRecord_Container = "Update tblCW1_ShipmentContainer
				Set cw_ContainerNumber='$CONTAINERNUMBER', cw_ContainerType='$CONTAINERTYPE',
				cw_ContainerDeliveryMode='$DELIVERYMODE', cw_ContainerDescription='$CATEGORYDESCRIPTION'
				WHERE cw_ContainerShipNumber = '$SHIPMENTKEY'";
				$updateRecContainer        = sqlsrv_query( $conn, $sqlUpdateRecord_Container );
				
                
            }
			
			returnShipKey( $SHIPMENTKEY, $CLIENT_ID );
            rename( $filename, $destination_path . pathinfo( $filename, PATHINFO_BASENAME ) );
        }
        
    }
    //END OF DATA SQL MANAGEMENT
    
}

//echo "Run Script Success";
?> 