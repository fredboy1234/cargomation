<?php

if(isset($GLOBALS['client_email'])){
	$user = $client_email;
	$CLIENT_ID = $_GET['user_id'];
	$myarray_order = glob("E:/A2BFREIGHT_MANAGER/$user/CW_XML/CW_CUSTOMS/IN/*.xml");

	usort($myarray_order, fn($a, $b) => filemtime($a) - filemtime($b));
		
		foreach ($myarray_order as $filename) {

		try {

			$parser = new __Services_JSON(SERVICES_JSON_LOOSE_TYPE); 
			$myxmlfilecontent = file_get_contents($filename); 
			$xml = simplexml_load_string($myxmlfilecontent);
			$universalshipment = json_encode($xml, JSON_PRETTY_PRINT);
			$universal_shipment = json_decode($universalshipment, true);

			$pathDataSource = "$.Body.UniversalShipment.Shipment";
			$path_DataSourceTransport="$.Body.UniversalShipment.Shipment.TransportLegCollection";
			$path_DataSourceOrganizationAddress="$.Body.UniversalShipment.Shipment.OrganizationAddressCollection";

			/*GET CUSTOM DEC KEY*/
			$key= jsonPath($universal_shipment, $pathDataSource.".DataContext.DataSourceCollection.DataSource.Key");
			$key = $parser->encode($key);
		    $key = node_exist(getArrayName($key));

		    /*GET WayBillNumber*/
		    $WayBillNumber= jsonPath($universal_shipment, $pathDataSource.".WayBillNumber");
			$WayBillNumber = $parser->encode($WayBillNumber);
		    $WayBillNumber = node_exist(getArrayName($WayBillNumber));

			 /*GET TransportMode*/
		    $TransportMode= jsonPath($universal_shipment, $pathDataSource.".TransportMode.Code");
			$TransportMode = $parser->encode($TransportMode);
		    $TransportMode = node_exist(getArrayName($TransportMode));

		     /*GET VesselName*/
		    $VesselName= jsonPath($universal_shipment, $pathDataSource.".VesselName");
			$VesselName = $parser->encode($VesselName);
		    $VesselName = node_exist(getArrayName($VesselName));

		     /*GET VoyageFlightNo*/
		    $VoyageFlightNo= jsonPath($universal_shipment, $pathDataSource.".VoyageFlightNo");
			$VoyageFlightNo = $parser->encode($VoyageFlightNo);
		    $VoyageFlightNo = node_exist(getArrayName($VoyageFlightNo));

		     /*GET LloydsIMO*/
		    $LloydsIMO= jsonPath($universal_shipment, $pathDataSource.".LloydsIMO");
			$LloydsIMO = $parser->encode($LloydsIMO);
		    $LloydsIMO = node_exist(getArrayName($LloydsIMO));

		     /*GET ContainerMode*/
		    $CustomsContainerMode= jsonPath($universal_shipment, $pathDataSource.".CustomsContainerMode.Code");
			$CustomsContainerMode = $parser->encode($CustomsContainerMode);
		    $CustomsContainerMode = node_exist(getArrayName($CustomsContainerMode));

		     /*GET PortOfLoading*/
		    $PortOfLoading= jsonPath($universal_shipment, $pathDataSource.".PortOfLoading.Name");
			$PortOfLoading = $parser->encode($PortOfLoading);
		    $PortOfLoading = node_exist(getArrayName($PortOfLoading));

		     /*GET PortOfDischarge*/
		    $PortOfDischarge= jsonPath($universal_shipment, $pathDataSource.".PortOfDischarge.Name");
			$PortOfDischarge = $parser->encode($PortOfDischarge);
		    $PortOfDischarge = node_exist(getArrayName($PortOfDischarge));

		     /*GET PortOfDischarge*/
		    $OwnerRef= jsonPath($universal_shipment, $pathDataSource.".OwnerRef");
			$OwnerRef = $parser->encode($OwnerRef);
		    $OwnerRef = node_exist(getArrayName($OwnerRef));

		     /*GET TotalVolume*/
		    $TotalVolume= jsonPath($universal_shipment, $pathDataSource.".TotalVolume");
			$TotalVolume = $parser->encode($TotalVolume);
		    $TotalVolume = node_exist(getArrayName($TotalVolume));


		     /*GET TRANSPORT COUNT*/
		    $LegOrderCount = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg");
			$LegOrderCount = $LegOrderCount;
				if ($LegOrderCount != false) {
				    if(count($LegOrderCount[0]) < 5){
				    $LegOrderCount = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg");
					$LegOrderCount = count($LegOrderCount[0]);
				    }
				    else{
				    $LegOrderCount = 0;
				    }	
				}
           
		     /*GET ETA ETD TRANSPORT*/
		    $items = array();
		    $orgaddress_array = array();
		    if($LegOrderCount == 0 || $LegOrderCount == '' ){
		  			/*GET ETA*/
				    $XPATH_ETA= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.EstimatedArrival");
					$XPATH_ETA = $parser->encode($XPATH_ETA);
				    $ETA = node_exist(getArrayName($XPATH_ETA));

				     /*GET ETD*/
				    $XPATH_ETD= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.EstimatedDeparture");
					$XPATH_ETD = $parser->encode($XPATH_ETD);
				    $ETD = node_exist(getArrayName($XPATH_ETD));

				     /*GET ACTUAL ARRIVAL*/
				    $XPATH_ATA= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.ActualArrival");
					$XPATH_ATA = $parser->encode($XPATH_ATA);
				    $ACTUAL_ARRIVAL = node_exist(getArrayName($XPATH_ATA));

				     /*GET ETD*/
				    $XPATH_ATD= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.ActualDeparture");
					$XPATH_ATD = $parser->encode($XPATH_ATD);
				    $ACTUAL_DEPARTURE = node_exist(getArrayName($XPATH_ATD));

				    $XPATH_TRANSMODE = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.LegOrder");
					$TRANSMODE_LEG = $parser->encode($XPATH_TRANSMODE);
					$LEG_ORDER = node_exist(getArrayName($TRANSMODE_LEG));

		   		 	 //GET VESSEL LLOYDS
					$XPATH_VESSELLOYDSIMO = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.VesselLloydsIMO");
					$VESSELLOYDSIMO = $parser->encode($XPATH_VESSELLOYDSIMO);
					$VESSELLOYDSIMO = node_exist(getArrayName($VESSELLOYDSIMO));

					//GET VESSEL LEG TYPE
					$XPATH_TRANSLEGTYPE = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.LegType");
					$XPATH_TRANSLEGTYPE = $parser->encode($XPATH_TRANSLEGTYPE);
					$LEG_TYPE = node_exist(getArrayName($XPATH_TRANSLEGTYPE));

					//GET VESSEL NAME TRANSPORT
					$XPATH_TRANSVESSELNAME = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.VesselName");
					$XPATH_TRANSVESSELNAME = $parser->encode($XPATH_TRANSVESSELNAME);
					$TRANSVESSELNAME = node_exist(getArrayName($XPATH_TRANSVESSELNAME));

					//GET DISCHARGE NAME
					$XPATH_TRANSDISCHARGE = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.PortOfDischarge.Name");
					$XPATH_TRANSDISCHARGE = $parser->encode($XPATH_TRANSDISCHARGE);
					$TRANSDISCHARGE = node_exist(getArrayName($XPATH_TRANSDISCHARGE));
					$TRANSDISCHARGE = str_replace("'", "", $TRANSDISCHARGE);

					//GET LOADING NAME
					$XPATH_TRANSLOADING = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.PortOfLoading.Name");
					$XPATH_TRANSLOADING = $parser->encode($XPATH_TRANSLOADING);
					$TRANSLOADING = node_exist(getArrayName($XPATH_TRANSLOADING));
					$TRANSLOADING = str_replace("'", "", $TRANSLOADING);

					//GET BOOKING CODE
					$XPATH_BOOKINGSTATUS = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.BookingStatus.Code");
					$XPATH_BOOKINGSTATUS = $parser->encode($XPATH_BOOKINGSTATUS);
					$BOOKINGSTATUS = node_exist(getArrayName($XPATH_BOOKINGSTATUS));
					
					//GET BOOKING DESC
					$XPATH_BOOKINGDESC= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.BookingStatus.Description");
					$XPATH_BOOKINGDESC = $parser->encode($XPATH_BOOKINGDESC);
					$BOOKINGDESC = node_exist(getArrayName($XPATH_BOOKINGDESC));

					//GET CARRIER DETAILS
					$XPATH_CARRIERTYPE= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.Carrier.AddressType");
					$XPATH_CARRIERTYPE = $parser->encode($XPATH_CARRIERTYPE);
					$CARRIERTYPE = node_exist(getArrayName($XPATH_CARRIERTYPE));

					//GET CARRIER COMPANY NAME
					$XPATH_CARRIERNAME= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.Carrier.CompanyName");
					$XPATH_CARRIERNAME = $parser->encode($XPATH_CARRIERNAME);
					$CARRIERNAME = node_exist(getArrayName($XPATH_CARRIERNAME));

					//GET CARRIER COMPANY NAME
					$XPATH_CARRIERORG= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.Carrier.OrganizationCode");
					$XPATH_CARRIERORG = $parser->encode($XPATH_CARRIERORG);
					$CARRIERORG = node_exist(getArrayName($XPATH_CARRIERORG));

					//GET LCL DATE AVAILABILITY
					$XPATH_LCLAvailability= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.LCLAvailability");
					$XPATH_LCLAvailability = $parser->encode($XPATH_LCLAvailability);
					$LCLAvailability = node_exist(getArrayName($XPATH_LCLAvailability));

					//GET LCL DATE STORAGE
					$XPATH_LCLStorageDate= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.LCLStorageDate");
					$XPATH_LCLStorageDate = $parser->encode($XPATH_LCLStorageDate);
					$LCLStorageDate = node_exist(getArrayName($XPATH_LCLStorageDate));

					
					$items[] = array("LegOrder"=>$LEG_ORDER,"LegType"=>$LEG_TYPE,"VesselName"=>$TRANSVESSELNAME,"Destination"=>$TRANSDISCHARGE,"Origin"=>$TRANSLOADING,"ETA"=>$ETA,"ETD"=>$ETD,"BookingStatus"=>$BOOKINGSTATUS,"BookingDesc"=>$BOOKINGDESC,"AddressType"=>$CARRIERTYPE,"CarrierName"=>$CARRIERNAME,"CarrierOrg"=>$CARRIERORG,"LCLAvailability"=>$LCLAvailability,"LCLStorageDate"=>$LCLStorageDate);

		    
		    }
		    else{
		    for ($a = 0; $a <= $LegOrderCount-1; $a++) {
		    if((int)$LegOrderCount-1 == $a){

				    /*GET ETA*/
				    $XPATH_ETA= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].EstimatedArrival");
					$XPATH_ETA = $parser->encode($XPATH_ETA);
				    $ETA = node_exist(getArrayName($XPATH_ETA));

				     /*GET ETD*/
				    $XPATH_ETD= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].EstimatedDeparture");
					$XPATH_ETD = $parser->encode($XPATH_ETD);
				    $ETD = node_exist(getArrayName($XPATH_ETD));

				     /*GET ACTUAL ARRIVAL*/
				    $XPATH_ATA= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].ActualArrival");
					$XPATH_ATA = $parser->encode($XPATH_ATA);
				    $ACTUAL_ARRIVAL = node_exist(getArrayName($XPATH_ATA));

				     /*GET ACTUAL DEPARTURE*/
				    $XPATH_ATD= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].ActualDeparture");
					$XPATH_ATD = $parser->encode($XPATH_ATD);
				    $ACTUAL_DEPARTURE = node_exist(getArrayName($XPATH_ATD));
			
		   		 	}  

		   		 	 /*GET ETA*/
				    $XPATH_ETA= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].EstimatedArrival");
					$XPATH_ETA = $parser->encode($XPATH_ETA);
				    $LEG_ETA = node_exist(getArrayName($XPATH_ETA));

				     /*GET ETD*/
				    $XPATH_ETD= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].EstimatedDeparture");
					$XPATH_ETD = $parser->encode($XPATH_ETD);
				    $LEG_ETD = node_exist(getArrayName($XPATH_ETD));

		   		 	/*GET LEG ORDER*/
		    	 	$XPATH_TRANSMODE = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].LegOrder");
					$TRANSMODE_LEG = $parser->encode($XPATH_TRANSMODE);
					$LEG_ORDER = node_exist(getArrayName($TRANSMODE_LEG));

		   		 	 //GET VESSEL LLOYDS
					$XPATH_VESSELLOYDSIMO = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].VesselLloydsIMO");
					$VESSELLOYDSIMO = $parser->encode($XPATH_VESSELLOYDSIMO);
					$VESSELLOYDSIMO = node_exist(getArrayName($VESSELLOYDSIMO));

					//GET VESSEL LEG TYPE
					$XPATH_TRANSLEGTYPE = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].LegType");
					$XPATH_TRANSLEGTYPE = $parser->encode($XPATH_TRANSLEGTYPE);
					$LEG_TYPE = node_exist(getArrayName($XPATH_TRANSLEGTYPE));

					//GET VESSEL NAME TRANSPORT
					$XPATH_TRANSVESSELNAME = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].VesselName");
					$XPATH_TRANSVESSELNAME = $parser->encode($XPATH_TRANSVESSELNAME);
					$TRANSVESSELNAME = node_exist(getArrayName($XPATH_TRANSVESSELNAME));

					//GET DISCHARGE NAME
					$XPATH_TRANSDISCHARGE = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].PortOfDischarge.Name");
					$XPATH_TRANSDISCHARGE = $parser->encode($XPATH_TRANSDISCHARGE);
					$TRANSDISCHARGE = node_exist(getArrayName($XPATH_TRANSDISCHARGE));
					$TRANSDISCHARGE = str_replace("'", "", $TRANSDISCHARGE);

					//GET LOADING NAME
					$XPATH_TRANSLOADING = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].PortOfLoading.Name");
					$XPATH_TRANSLOADING = $parser->encode($XPATH_TRANSLOADING);
					$TRANSLOADING = node_exist(getArrayName($XPATH_TRANSLOADING));
					$TRANSLOADING = str_replace("'", "", $TRANSLOADING);

					//GET BOOKING CODE
					$XPATH_BOOKINGSTATUS = jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].BookingStatus.Code");
					$XPATH_BOOKINGSTATUS = $parser->encode($XPATH_BOOKINGSTATUS);
					$BOOKINGSTATUS = node_exist(getArrayName($XPATH_BOOKINGSTATUS));
					
					//GET BOOKING DESC
					$XPATH_BOOKINGDESC= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].BookingStatus.Description");
					$XPATH_BOOKINGDESC = $parser->encode($XPATH_BOOKINGDESC);
					$BOOKINGDESC = node_exist(getArrayName($XPATH_BOOKINGDESC));

					//GET CARRIER DETAILS
					$XPATH_CARRIERTYPE= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].Carrier.AddressType");
					$XPATH_CARRIERTYPE = $parser->encode($XPATH_CARRIERTYPE);
					$CARRIERTYPE = node_exist(getArrayName($XPATH_CARRIERTYPE));

					//GET CARRIER COMPANY NAME
					$XPATH_CARRIERNAME= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].Carrier.CompanyName");
					$XPATH_CARRIERNAME = $parser->encode($XPATH_CARRIERNAME);
					$CARRIERNAME = node_exist(getArrayName($XPATH_CARRIERNAME));

					//GET CARRIER COMPANY NAME
					$XPATH_CARRIERORG= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].Carrier.OrganizationCode");
					$XPATH_CARRIERORG = $parser->encode($XPATH_CARRIERORG);
					$CARRIERORG = node_exist(getArrayName($XPATH_CARRIERORG));

					//GET LCL DATE AVAILABILITY
					$XPATH_LCLAvailability= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].LCLAvailability");
					$XPATH_LCLAvailability = $parser->encode($XPATH_LCLAvailability);
					$LCLAvailability = node_exist(getArrayName($XPATH_LCLAvailability));

					//GET LCL DATE STORAGE
					$XPATH_LCLStorageDate= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg[$a].LCLStorageDate");
					$XPATH_LCLStorageDate = $parser->encode($XPATH_LCLStorageDate);
					$LCLStorageDate = node_exist(getArrayName($XPATH_LCLStorageDate));

					
					$items[] = array("LegOrder"=>$LEG_ORDER,"LegType"=>$LEG_TYPE,"VesselName"=>$TRANSVESSELNAME,"Destination"=>$TRANSDISCHARGE,"Origin"=>$TRANSLOADING,"ETA"=>$LEG_ETA,"ETD"=>$LEG_ETD,"BookingStatus"=>$BOOKINGSTATUS,"BookingDesc"=>$BOOKINGDESC,"AddressType"=>$CARRIERTYPE,"CarrierName"=>$CARRIERNAME,"CarrierOrg"=>$CARRIERORG,"LCLAvailability"=>$LCLAvailability,"LCLStorageDate"=>$LCLStorageDate);
				}
			}

			 $routing = json_encode($items);

			   /*GET ORGANIZATION COUNT*/
		    $ORGADDRESS = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress");
			$ORGADDRESSCTR = $ORGADDRESS;
				if ($ORGADDRESSCTR != false) {
					$ORGADDRESSCTR = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress");
					$ORGADDRESSCTR = count($ORGADDRESSCTR[0]);
				}
				else
				{
					$ORGADDRESSCTR = 0;
			} 


			for ($b = 0; $b <= $ORGADDRESSCTR-1; $b++) {
		  	$CUSTOM_ADDRESSTYPE = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].AddressType");
			$CUSTOM_ADDRESSTYPE = $parser->encode($CUSTOM_ADDRESSTYPE);
		    $CUSTOM_ADDRESSTYPE = node_exist(getArrayName($CUSTOM_ADDRESSTYPE));

		    if($CUSTOM_ADDRESSTYPE == "ImporterPickupDeliveryAddress"){
		    $PLACE_DELIVERY = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Address1");
			$PLACE_DELIVERY = $parser->encode($PLACE_DELIVERY);
		    $PLACE_DELIVERY = node_exist(str_replace("'","",getArrayName($PLACE_DELIVERY)));
			}

			if($CUSTOM_ADDRESSTYPE == "SupplierPickupDeliveryAddress"){
		    $PLACE_RECEIPT = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Address1");
			$PLACE_RECEIPT = $parser->encode($PLACE_RECEIPT);
		    $PLACE_RECEIPT = node_exist(str_replace("'","",getArrayName($PLACE_RECEIPT)));
			}

			if($CUSTOM_ADDRESSTYPE == "ImporterDocumentaryAddress"){
		    $CONSIGNEE = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].OrganizationCode");
			$CONSIGNEE = $parser->encode($CONSIGNEE);
		    $CONSIGNEE = node_exist(str_replace("'","",getArrayName($CONSIGNEE)));

		    $CONSIGNEE_ADDRESS1 = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Address1");
			$CONSIGNEE_ADDRESS1 = $parser->encode($CONSIGNEE_ADDRESS1);
		    $CONSIGNEE_ADDRESS1 = node_exist(str_replace("'","",getArrayName($CONSIGNEE_ADDRESS1)));

		    $CONSIGNEE_CITY = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].City");
			$CONSIGNEE_CITY = $parser->encode($CONSIGNEE_CITY);
		    $CONSIGNEE_CITY = node_exist(str_replace("'","",getArrayName($CONSIGNEE_CITY)));
		    $CONSIGNEE_ADDRESS = $CONSIGNEE_ADDRESS1.", ".$CONSIGNEE_CITY;		    
			}

			if($CUSTOM_ADDRESSTYPE == "SupplierDocumentaryAddress"){
		    $CONSIGNOR = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].OrganizationCode");
			$CONSIGNOR = $parser->encode($CONSIGNOR);
		    $CONSIGNOR = node_exist(str_replace("'","",getArrayName($CONSIGNOR)));

		    $CONSIGNOR_ADDRESS1 = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Address1");
			$CONSIGNOR_ADDRESS1 = $parser->encode($CONSIGNOR_ADDRESS1);
		    $CONSIGNOR_ADDRESS1 = node_exist(str_replace("'","",getArrayName($CONSIGNOR_ADDRESS1)));

		    $CONSIGNOR_CITY = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].City");
			$CONSIGNOR_CITY = $parser->encode($CONSIGNOR_CITY);
		    $CONSIGNOR_CITY = node_exist(str_replace("'","",getArrayName($CONSIGNOR_CITY)));
		    $CONSIGNOR_ADDRESS = $CONSIGNOR_ADDRESS1.", ".$CONSIGNOR_CITY;		    
			}

			$XPATH_ORGANIZATIONCODE = jsonPath($universal_shipment,$path_DataSourceOrganizationAddress.".OrganizationAddress[$b].OrganizationCode");
					
			$XPATH_ADDRESS1 = jsonPath($universal_shipment,$path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Address1");
			$XPATH_ADDRESS2 = jsonPath($universal_shipment,$path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Address2");
			$XPATH_ADDRESSCODE = jsonPath($universal_shipment,$path_DataSourceOrganizationAddress.".OrganizationAddress[$b].AddressShortCode");
			$XPATH_COMPANYNAME = jsonPath($universal_shipment,$path_DataSourceOrganizationAddress.".OrganizationAddress[$b].OrganizationCode");
			$XPATH_PORTNAME = jsonPath($universal_shipment,$path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Port.Name");
			$XPATH_STATE = jsonPath($universal_shipment,$path_DataSourceOrganizationAddress.".OrganizationAddress[$b].State");
			$XPATH_POSTCODE = jsonPath($universal_shipment,$path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Postcode");
			$XPATH_COUNTRY = jsonPath($universal_shipment,$path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Country.Name");
			$XPATH_ADDRESSTYPE = jsonPath($universal_shipment,$path_DataSourceOrganizationAddress.".OrganizationAddress[$b].AddressType");
			$XPATH_COMPNAME= jsonPath($universal_shipment,$path_DataSourceOrganizationAddress.".OrganizationAddress[$b].CompanyName");

								
			$XPATH_EMAIL= jsonPath($universal_shipment,$path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Email");
			$XPATH_EMAIL_GLOBAL = $parser->encode($XPATH_EMAIL);
			$EMAIL = node_exist(getArrayName($XPATH_EMAIL_GLOBAL));


			/*store to json for organization details*/
			$orgaddress_array[] = array("AddressType"=>getArrayName($CUSTOM_ADDRESSTYPE),"Address1"=>node_exist(getArrayName($parser->encode($XPATH_ADDRESS1))),"Address2"=>node_exist(getArrayName($parser->encode($XPATH_ADDRESS2))),"AddressShortCode"=>node_exist(getArrayName($parser->encode($XPATH_ADDRESSCODE))),"CompanyName"=>node_exist(getArrayName($parser->encode($XPATH_COMPNAME))),"Email"=>$EMAIL,"OrganizationCode"=>node_exist(getArrayName($parser->encode($XPATH_ORGANIZATIONCODE))));

		    }

		  $organization = json_encode($orgaddress_array);

		  $sql = "SELECT * FROM dbo.shipment WHERE dbo.shipment.shipment_num = '$key' AND dbo.shipment.user_id ='$_GET['user_id']'";
		  $qryCustomDec = sqlsrv_query($conn, $sql, array(), array( "Scrollable" => 'static'));
		  $qryCustomDec = sqlsrv_num_rows($qryCustomDec);

		   if($qryCustomDec<=0){
		  	 $sqlorderinsert = "INSERT INTO shipment
                (user_id ,console_id, shipment_num, master_bill, house_bill, transport_mode,
                vessel_name, voyage_flight_num, vesslloyds, eta, etd, place_delivery, place_receipt,
				consignee, consignor, sending_agent, receiving_agent, receiving_agent_addr, sending_agent_addr, consignee_addr, consignor_addr, trigger_date, container_mode, port_loading, port_discharge,order_number,totalvolume,ata,atd,route_leg,organization,packingline)
                Values(" . $_GET['user_id'] . ",'','" . $key . "','" . $WayBillNumber . "','','" . $TransportMode . "','" . $VesselName . "','" . $VoyageFlightNo . "','" . $LloydsIMO . "','" . $ETA . "','" . $ETD . "','" . $PLACE_DELIVERY . "','" . $PLACE_RECEIPT . "',
				'" . $CONSIGNEE . "','" . $CONSIGNOR . "','','','','','" . $CONSIGNEE_ADDRESS . "','" . $CONSIGNOR_ADDRESS . "','','".$CustomsContainerMode."','".$PortOfLoading."','".$PortOfDischarge."','".$OwnerRef."','".$TotalVolume."','".$ACTUAL_ARRIVAL."','".$ACTUAL_DEPARTURE."','".$routing."','".$organization."','') SELECT SCOPE_IDENTITY() as id_ship";
		  	$sqlorderinsert = sqlsrv_query($conn, $sqlorderinsert, array(), array( "Scrollable" => 'static'));

		  
		  }
		  else
		  {
		  	$sqlUpdateRecord = "Update shipment
				        Set master_bill ='$WayBillNumber', transport_mode='$TransportMode',
				        vessel_name='$VesselName', voyage_flight_num='$VoyageFlightNo', vesslloyds='$LloydsIMO', eta='$ETA', etd='$ETD', place_delivery='$PLACE_DELIVERY', place_receipt='$PLACE_RECEIPT',
				        consignee='$CONSIGNEE',consignor='$CONSIGNOR', consignee_addr='$CONSIGNEE_ADDRESS',consignor_addr='$CONSIGNOR_ADDRESS',container_mode='$CustomsContainerMode', port_loading='$PortOfLoading', port_discharge='$PortOfDischarge', order_number='$OwnerRef', totalvolume='$TotalVolume', ata='$ACTUAL_ARRIVAL', atd='$ACTUAL_DEPARTURE', route_leg='$routing', organization='$organization'
				        WHERE shipment_num = '$key' AND user_id = '$CLIENT_ID'";
			$sqlUpdateRecord = sqlsrv_query($conn, $sqlUpdateRecord, array(), array( "Scrollable" => 'static'));

			
		  }

		  	$destination_path = "E:/A2BFREIGHT_MANAGER/$client_email/CW_XML/CW_CUSTOMS/SUCCESS/";						
			if(!file_exists($destination_path.$filename)){
			rename($filename, $destination_path . pathinfo($filename, PATHINFO_BASENAME));
			}

		}/*end try catch*/
		 catch (Exception $e) {
			
		}
	}
}
