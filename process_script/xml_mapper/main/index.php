<?php
###Title   : Process Script Mapper
###Author  : Freidrich V.
###Version : 2.0
require_once (base64_decode('ZnVuY3Rpb24ucGhw'));

if(isset($_GET['user_id'])){

	$client_email = getClientEmail($_GET['user_id']);
	$web_service = getWebService($_GET['user_id']);
	$file_path = glob("E:/A2BFREIGHT_MANAGER/$client_email/CW_XML/*.xml");
	usort($file_path, fn($a, $b) => filemtime($a) - filemtime($b));

	###Loop all Client XML path
	if($web_service	!= 'Web Service Failed'){
	foreach($file_path as $filename) {
		$xml = XMLtoJSON($filename);
		$dataContext = parseJson($xml, $path_DataSource,"");

		if($dataContext != 'false'){
			$dataContextDecode =  json_decode(str_replace(array("[[", "]]"), array("[", "]"), $dataContext),true);
			$dataContextCount =  count($dataContextDecode);
			$ship_array = array();
			$container_in = array();
			$container_keys = array();
			$shipNumber = "";
			###Identify ForwardingShipment XML
			foreach ($dataContextDecode as $val=> $valueContext){
			 	if($valueContext['Type'] == "ForwardingShipment"){
			 		$shipNumber = $valueContext['Key'];
			 		$xmlType = $valueContext['Type'];
			 	}
			 	elseif($valueContext['Type'] == "ForwardingConsol"){
			 		$consolNumber = $valueContext['Key'];
			 		$xmlType = $valueContext['Type'];
			 	}
			}
			###End of Identify ForwardingShipment XML

			if($shipNumber !== ""){
			###Get Shipment Details
			$master_bill = node_exist(getArrayName(parseJson($xml, $path_UniversalShipment,".WayBillNumber")));
			$house_bill = node_exist(getArrayName(parseJson($xml, $path_UniversalSubShipment,".WayBillNumber")));
			$transport_mode = node_exist(getArrayName(parseJson($xml, $path_UniversalShipment,".TransportMode.Code")));
			$vessel_name = node_exist(getArrayName(parseJson($xml, $path_UniversalShipment,".VesselName")));
			$voyage_number = node_exist(getArrayName(parseJson($xml, $path_UniversalShipment,".VoyageFlightNo")));
			$lloyds_imo = node_exist(getArrayName(parseJson($xml, $path_UniversalShipment,".LloydsIMO")));
			$place_delivery = node_exist(getArrayName(parseJson($xml, $path_UniversalShipment,".PlaceOfDelivery.Name")));
			$place_receipt = node_exist(getArrayName(parseJson($xml, $path_UniversalShipment,".PlaceOfReceipt.Name")));
			$triggered_date = node_exist(getArrayName(parseJson($xml, $path_UniversalShipment,".DataContext.TriggerDate")));
			$container_mode = node_exist(getArrayName(parseJson($xml, $path_UniversalShipment,".ContainerMode.Code")));
			$port_loading = node_exist(getArrayName(parseJson($xml, $path_UniversalShipment,".PortOfLoading.Name")));
			$port_discharge = node_exist(getArrayName(parseJson($xml, $path_UniversalShipment,".PortOfDischarge.Name")));
			$total_volume = node_exist(getArrayName(parseJson($xml, $path_UniversalShipment,".TotalVolume")));
			$packing_line = str_replace(array("[[", "]]"), array("[", "]"), parseJson($xml, $path_PackingLineCollection,".PackingLine"));
			$packing_linedecode = json_decode($packing_line,true);
			$route_leg = str_replace(array("[[", "]]"), array("[", "]"), parseJson($xml, $path_TransportLegCollection,""));
			$container = str_replace(array("[[", "]]"), array("[", "]"), parseJson($xml, $path_ContainerCollection,""));
			$milestone = str_replace(array("[[", "]]"), array("[", "]"), parseJson($xml, $path_MilestoneCollection,""));
			$container_decode = json_decode($container,true);


			###Get specific container from packingline to containercollection
			if(count($packing_linedecode)>0){
				foreach($packing_linedecode as $keycontainer=>$valueContainer) {
		    		array_push($container_in,$valueContainer['ContainerNumber']);		
		    	}
			}

			if($container != 'false'){
				 if(count($container_decode) === 1){
		    		foreach ($container_decode as $key => $value) {
		    			if(in_array($value['ContainerNumber'],$container_in)){
		    				$containercollection = str_replace(array("'", "'"), array("", ""),$container);
		    			}
		    		}
		    	}
		    	else{
		    		foreach($container_in as $value_con => $val){
			    		foreach ($container_decode as $key => $value) {
			    			 if($val === $value['ContainerNumber']){
			    			 	array_push($container_keys,$value_con);
			    			}
			    		}
			    	}
			    	$get_container = array();
			    	if(count($container_keys) > 0){
			    		foreach($container_keys as $keys){
			    			array_push($get_container,str_replace(array("[[", "]]"), array("", ""),parseJson($xml, $path_ContainerCollectionMultiple,".[$keys]")));
			    		}
			    	  $a = "";
			    	  $containerctr =  json_decode(json_encode($get_container, JSON_UNESCAPED_SLASHES));
			    	   foreach ($containerctr as $key => $value) {
			    	 	if(count($containerctr) > 1){
			    	 		if ($key === array_key_first($containerctr)) {
			    	 		  		 $a .= substr($value, 0, -1).',';
			    	 		  }
			    	 		  elseif ($key === array_key_last($containerctr)) {
			    	 		  		 $a .= substr($value, 1, -1).']';
			    	 		  }else{
			    	 		  		 $a .= substr(substr($value, 0, -1),1,-1).',';
			    	 		}
			    	 	 }else{	$a .= substr($value, 0, -1).']'; }
			    	   }
			    	   $containercollection = str_replace(array("'", "'"), array("", ""),$a);
			    	}
		    	}
			}
		    ###End of Get specific container from packingline to containercollection

			###Get Last leg details of transport collection
			$dataTransportLeg = parseJson($xml, $path_TransportLegCollection,"");
			$dataTransDecode =  json_decode(str_replace(array("[[", "]]"), array("[", "]"), $dataTransportLeg),true);
			$dataTransCount =  count($dataTransDecode);
			$i = 0;
			foreach($dataTransDecode as $key=>$valueTransLeg) {
			 if($dataTransCount > 1){
			 	if ($key === array_key_first($dataTransDecode)) {
			        $etd = $valueTransLeg['EstimatedDeparture'];
			        $atd = $valueTransLeg['ActualDeparture'];
			    }
			  elseif ($key === array_key_last($dataTransDecode)) {
			        $eta = $valueTransLeg['EstimatedArrival'];
			        $ata = $valueTransLeg['ActualArrival'];
			  		 }
				}
				else{
					$eta = $valueTransLeg['EstimatedArrival'];
				    $ata = $valueTransLeg['ActualArrival'];
				    $etd = $valueTransLeg['EstimatedDeparture'];
				    $atd = $valueTransLeg['ActualDeparture'];
				}
			} 
			###End of Get Last leg details of transport collection


			### Get Organization Collection / Contact Info
			if($consolNumber == ''){
				$path_Organization = $path_OrganizationAddressCollection;
				$path_Milestone = $path_MilestoneCollection;
			}else{
				$path_Organization = $path_SubOrganizationAddressCollection;
				$path_Milestone = $path_MilestoneCollectionSub;
			}
			$dataOrganizationCollection = parseJson($xml, $path_Organization,"");
			$milestone = parseJson($xml, $path_Milestone,"");
			$dataOrganizationDecode =  json_decode(str_replace(array("[[", "]]"), array("[", "]"), $dataOrganizationCollection),true);

			foreach($dataOrganizationDecode as $valueOrganization) {

				if(!empty($valueOrganization['State']) || !empty($valueOrganization['Postcode'])){
					$state = $valueOrganization['State'];
					$postcode = $valueOrganization['State'];
				}
				if($valueOrganization['AddressType'] == "ConsigneeDocumentaryAddress"){
					$consignee = $valueOrganization['OrganizationCode'];
					$consignee_add = $valueOrganization['Address1'].' , '.$valueOrganization['Country']['Name'];
				}
				elseif($valueOrganization['AddressType'] == "ConsignorDocumentaryAddress"){
					$consignor = $valueOrganization['OrganizationCode'];
					$consignor_add = $valueOrganization['Address1'].' , '.$valueOrganization['Country']['Name']; 
				}
				elseif($valueOrganization['AddressType'] == "SendingForwarderAddress"){
					$sending_agent = $valueOrganization['OrganizationCode'];
					$sending_add = $valueOrganization['Address1'].' , '.$state.' , '.$postcode.' , '.$valueOrganization['Country']['Name']; 
				}
				elseif($valueOrganization['AddressType'] == "ReceivingForwarderAddress"){
					$receiving_agent = $valueOrganization['OrganizationCode'];
					$receiving_add = $valueOrganization['Address1'].' , '.$state.' , '.$postcode.' , '.$valueOrganization['Country']['Name']; 
				}
				$postcode = '';
				$state =  '';
			}
			###End of Get Organization Collection / Contact Info
			###Get Order Collection
			$dataOrderCollection = str_replace(array("[[", "]]"), array("[", "]"),parseJson($xml, $path_OrderNumberCollection,".OrderNumber"));
			if($dataOrderCollection != 'false'){
				$order_number = $dataOrderCollection;
			}	

			###End of Order Collection
			###End of Get Shipment Details
			array_push($ship_array,$GLOBALS['user_id'],$consolNumber,$shipNumber,$master_bill,$house_bill,$transport_mode,$vessel_name,$voyage_number,$lloyds_imo,$eta,$etd);
			array_push($ship_array,removeSingleQuote($place_delivery),removeSingleQuote($place_receipt),$consignee,$consignor,$sending_agent,$receiving_agent,removeSingleQuote($receiving_add),removeSingleQuote($sending_add),removeSingleQuote($consignee_add),removeSingleQuote($consignor_add));
			array_push($ship_array,$triggered_date,$container_mode,removeSingleQuote($port_loading),removeSingleQuote($port_discharge),$order_number,$total_volume,$ata,$atd);
			array_push($ship_array,removeSingleQuote($route_leg),removeSingleQuote($dataOrganizationCollection),removeSingleQuote($packing_line),removeSingleQuote($containercollection),removeSingleQuote($milestone));
			
			###Process Shipment to Database
			if(ifShipmentExist($shipNumber) === false){
				$res = addShipment($ship_array,$shipNumber);
				if($res == 'failed'){
					moveFile($res,$filename);
					logFile("shipment_log.txt","failed to add Job #: ".$shipNumber.'');
				}else{
					moveFile($res,$filename);
					logFile("shipment_log.txt","Successfully added Job #".$shipNumber.'');
				}
			}else {
				$res = updateShipment($ship_array,$shipNumber);
				if($res == 'failed'){
					moveFile($res,$filename);
					logFile("shipment_log.txt","failed to update Job #: ".$shipNumber.''.$GLOBALS['err_update']);
				}else{
					moveFile($res,$filename);
					logFile("shipment_log.txt","Successfully updated Job #".$shipNumber.'');
				}
			}
			###End of Process Shipment to Database


			###Get customs brokerage , arinvoice , order
			require_once('customs.php');
			require_once('arinvoice.php');
			require_once('order.php');
			###End of Get customs brokerage , arinvoice , order

		}else{ 
			$ignored_Xml = "E:/A2BFREIGHT_MANAGER/$client_email/CW_ERROR/";
			if(!file_exists($ignored_Xml.$filename)){
				rename($filename, $ignored_Xml . pathinfo($filename, PATHINFO_BASENAME));
			}
		} 
	}
		else{
			###Ignore xml file if not xml for shipment
			$ignored_Xml = "E:/A2BFREIGHT_MANAGER/$client_email/CW_ERROR/";						
			if(!file_exists($ignored_Xml.$filename)){
				rename($filename, $ignored_Xml . pathinfo($filename, PATHINFO_BASENAME));
			}
			###End of Ignore xml file if not forwarding shipment
		} 
		curl_exec($GLOBALS['curl']);    
	}
}else{
	logFile("shipment_log.txt",$web_service);
	}
}
