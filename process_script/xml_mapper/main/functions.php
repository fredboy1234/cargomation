<?php
require_once ('json.php');
require_once ('jsonpath-0.8.1.php');
if(isset($_GET['web_link']) && isset($_GET['key']) && isset($_GET['e_id']) && isset($_GET['s_id']) && isset($_GET['auth'])){
$web_link=$_GET['web_link'];
$key=$_GET['key'];
$e_id=$_GET['e_id'];
$s_id=$_GET['s_id'];
$auth=$_GET['auth'];
$company_code=$_GET['ccode'];
	
}
	
    // $curl_ = curl_init();
    // curl_setopt_array( $curl_, array(
         // CURLOPT_URL => $web_link,
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => "",
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => "POST",
        // CURLOPT_POSTFIELDS => "<UniversalShipmentRequest xmlns=\"http://www.cargowise.com/Schemas/Universal/2011/11\" version=\"1.1\">\r\n
        // <ShipmentRequest>
        // <DataContext>
            // <DataTargetCollection>
                // <DataTarget>
                    // <Type>ForwardingShipment</Type>
                    // <Key>$key</Key>
                // </DataTarget>
            // </DataTargetCollection>
            // <Company>
                // <Code>$company_code</Code>
            // </Company>
            // <EnterpriseID>$e_id</EnterpriseID>
            // <ServerID>$s_id</ServerID>
        // </DataContext>
    // </ShipmentRequest>
        // </UniversalShipmentRequest>",
        // CURLOPT_HTTPHEADER => array(
            // "Authorization: Basic $auth",
            // "Content-Type: application/xml",
            // "Cookie: WEBSVC=f50e2886473c750f" 
        // ) 
    // ) );
	
	$curl_ = curl_init();
        curl_setopt_array($curl_, array(
            CURLOPT_URL => $web_link,
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
        <EnterpriseID>$e_id</EnterpriseID>\r\n
        <ServerID>$s_id</ServerID>\r\n
        </DataContext>\r\n
        </DocumentRequest>\r\n
        </UniversalDocumentRequest>",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic $auth",
                "Content-Type: application/xml",
                "Cookie: WEBSVC=f50e2886473c750f"
            )
        ));
		
        $parser = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        curl_setopt($curl_, CURLOPT_SSL_VERIFYPEER, false);
        $document_request = curl_exec($curl_);
        curl_close($curl_);
        $xml_docs = simplexml_load_string($document_request);
        echo $json_documentrequest = json_encode($xml_docs, JSON_PRETTY_PRINT);
        $json_xpathdoc = json_decode($json_documentrequest, true);
		
		// $doc_status = jsonPath($json_xpathdoc, "$.Status");
		// $doc_status = $parser->encode($doc_status);
		// $doc_status = getArrayName($doc_status);
		
		// if($doc_status != "ERR"){
			
			//CHECK NUMBER OF ATTACHMENT
			// $xpath_attachedno = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument.FileName");
			// $attachedno_parse = $parser->encode($xpath_attachedno);
			// $attachedno = getArrayName($attachedno_parse);
			
			//IF FALSE MEANS MULTIPLE ATTACHMENT
			// if($attachedno == "false"){
		    // $xpath_attachedno = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument");
			// $json_documentrequest = trim(json_encode($xpath_attachedno, JSON_PRETTY_PRINT),"[]");
			// $json_array  = json_decode($json_documentrequest, true);
			// $file_count  = (int)count($json_array);
			// }
			// else{
			// $file_count  = 1;	
			// }
			
			// for ($attach = 1;$attach <= $file_count;$attach++){
				// if($file_count == 1){
					// $xpath_AttachedCountSingle = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument.FileName");
					// $xpath_AttachedB64 = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument.ImageData");
					// $xpath_DocType = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument.Type.Code");
					// $xpath_SavedUtc = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument.SaveDateUTC");
					// $xpath_SavedBy = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument.SavedBy.Code");
					// $xpath_SavedEventTime = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.EventTime");
					// $SingleAttach_ctr = $parser->encode($xpath_AttachedCountSingle);
					// $SingleAttach_ctrb64 = $parser->encode($xpath_AttachedB64);
					// $DocType = $parser->encode($xpath_DocType);
					// $Saved_date = $parser->encode($xpath_SavedUtc);
					// $Saved_EventTime = $parser->encode($xpath_SavedEventTime);
					// $Saved_By = $parser->encode($xpath_SavedBy);
					// $ctr_1 = getArrayName($SingleAttach_ctr);
					// $ctr_b64 = getArrayName($SingleAttach_ctrb64);
					// $get_valDocType_ = getArrayName($DocType);
					// $get_valSavedDate = getArrayName($Saved_date);
					// $get_Saved_By = getArrayName($Saved_By);
					// $get_Saved_EventTime = getArrayName($Saved_EventTime);
					
							
				// }
				
				// else{
					// $xpath_AttachedCountSingle = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument[$attach].FileName");
					// $xpath_AttachedB64 = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument[$attach].ImageData");
					// $xpath_DocType = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument[$attach].Type.Code");
					// $xpath_SavedUtc = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument[$attach].SaveDateUTC");
					// $xpath_SavedBy = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument[$attach].SavedBy.Code");
					// $xpath_SavedEventTime = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.EventTime");
					// $SingleAttach_ctr = $parser->encode($xpath_AttachedCountSingle);
					// $SingleAttach_ctrb64 = $parser->encode($xpath_AttachedB64);
					// $DocType = $parser->encode($xpath_DocType);
					// $Saved_date = $parser->encode($xpath_SavedUtc);
					// $Saved_EventTime = $parser->encode($xpath_SavedEventTime);
					// $Saved_By = $parser->encode($xpath_SavedBy);
					// $ctr_1 = getArrayName($SingleAttach_ctr);
				    // $ctr_b64 = getArrayName($SingleAttach_ctrb64);
					// $get_valDocType_ = getArrayName($DocType);
					// $get_valSavedDate = getArrayName($Saved_date);
					// $get_Saved_By = getArrayName($Saved_By);
					// $get_Saved_EventTime = getArrayName($Saved_EventTime);
					
					// }
				
				
			// }

		// }
		// else{
			// echo "no edocs found";
	// }
		
		
		
		
		
		
		
		
    // $parser = new Services_JSON( SERVICES_JSON_LOOSE_TYPE );
    // curl_setopt( $curl_, CURLOPT_SSL_VERIFYPEER, false );
    // $document_request = curl_exec( $curl_ );
    // curl_close( $curl_ );
    // $xml_docs             = simplexml_load_string( $document_request );
    // $json_documentrequest = json_encode( $xml_docs, JSON_PRETTY_PRINT );
    // $json_xpathdoc        = json_decode( $json_documentrequest, true );
    
    // $XPATH_CONSOLNUMBER = jsonPath($json_xpathdoc, "$.Data.UniversalShipment.Shipment.DataContext.DataSourceCollection.DataSource.Key");
    // $CONSOLNUMBER = $parser->encode($XPATH_CONSOLNUMBER);
    // echo $CONSOLNUMBER = getArrayName($CONSOLNUMBER);

function getArrayName( $val )
{
    return str_replace( array(
        '["',
        '"]',
        '[',
        ']',
		'\/'
    ), array(
        "",
        "",
        "",
        "",
		"/"
    ), $val );
}
?>