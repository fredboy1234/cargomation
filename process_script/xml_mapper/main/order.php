<?php 
if(isset($_GET['get_email'])){
$user = $client_email;
$myarray_order = glob("E:/A2BFREIGHT_MANAGER/$user/CW_XML/Orders/IN/*.xml");
		usort($myarray_order, fn($a, $b) => filemtime($a) - filemtime($b));
		foreach ($myarray_order as $filename) {
		try{
			$parser = new __Services_JSON(SERVICES_JSON_LOOSE_TYPE);
			$myxmlfilecontent = file_get_contents($filename);
			$xml = simplexml_load_string($myxmlfilecontent);
			$universalshipment = json_encode($xml, JSON_PRETTY_PRINT);
			$universal_shipment = json_decode($universalshipment, true);


			$pathDataSource = "$.Shipment";
			$pathDataSourceContext = "$.Shipment.DataContext.DataSourceCollection";
			$path_DataSourceOrder ="$.Shipment.Order";
			$path_DataSourceMileStone ="$.Shipment.MilestoneCollection";
			$path_DataSourceOrganizationAddress="$.Shipment.OrganizationAddressCollection";
			$path_DataSourceTransport="$.Shipment.TransportLegCollection";
			$path_DataSourceContainer="$.Shipment.ContainerCollection";

			/*GET KEYCONTEXT DETAILS*/
			$XPATH_KEYTYPE= jsonPath($universal_shipment, $pathDataSourceContext.".DataSource.Type");
			$XPATH_KEYTYPE = $parser->encode($XPATH_KEYTYPE);
		    $KEYTYPE = node_exist(getArrayName($XPATH_KEYTYPE));

		    /*GET KEYCONTEXT DETAILS*/
			$XPATH_KEY= jsonPath($universal_shipment, $pathDataSourceContext.".DataSource.Key");
			$XPATH_KEY = $parser->encode($XPATH_KEY);
			$KEY = node_exist(getArrayName($XPATH_KEY));

			/*GET ORDER DETAILS*/
			$XPATH_ORDERNUMBER = jsonPath($universal_shipment, $path_DataSourceOrder.".OrderNumber");
			$XPATH_ORDERNUMBER = $parser->encode($XPATH_ORDERNUMBER);
		    $ORDERNUMBER = node_exist(getArrayName($XPATH_ORDERNUMBER));

		    $XPATH_ORDERSTATUS = jsonPath($universal_shipment, $path_DataSourceOrder.".Status.Code");
			$XPATH_ORDERSTATUS = $parser->encode($XPATH_ORDERSTATUS);
		    $ORDERSTATUS = node_exist(getArrayName($XPATH_ORDERSTATUS));

		    $XPATH_ORDERSTATDES = jsonPath($universal_shipment, $path_DataSourceOrder.".Status.Description");
			$XPATH_ORDERSTATDES = $parser->encode($XPATH_ORDERSTATDES);
		    $ORDERSTATDES = node_exist(getArrayName($XPATH_ORDERSTATDES));
            
            /*GET PORTLOADING*/
		    $XPATH_POL = jsonPath($universal_shipment, $pathDataSource.".PortOfLoading.Code");
			$XPATH_POL = $parser->encode($XPATH_POL);
		    $POL = node_exist(getArrayName($XPATH_POL));

		     /*GET PORTDISCHARGE*/
		    $XPATH_POD = jsonPath($universal_shipment, $pathDataSource.".PortOfDischarge.Code");
			$XPATH_POD = $parser->encode($XPATH_POD);
		    $POD = node_exist(getArrayName($XPATH_POD));

		     /*GET TRANSPORTMODE*/
		    $XPATH_TRANSPORTMODE = jsonPath($universal_shipment, $pathDataSource.".TransportMode.Code");
			$XPATH_TRANSPORTMODE = $parser->encode($XPATH_TRANSPORTMODE);
		    $TRANSPORTMODE = node_exist(getArrayName($XPATH_TRANSPORTMODE));

		     /*GET TOTAL VOLUME*/
		    $XPATH_TOTALVOLUME = jsonPath($universal_shipment, $pathDataSource.".TotalVolume");
			$XPATH_TOTALVOLUME = $parser->encode($XPATH_TOTALVOLUME);
		    $TOTALVOLUME = node_exist(getArrayName($XPATH_TOTALVOLUME));

		     /*GET TOTALWEIGHT*/
		    $XPATH_TOTALWEIGHT = jsonPath($universal_shipment, $pathDataSource.".TotalWeight");
			$XPATH_TOTALWEIGHT = $parser->encode($XPATH_TOTALWEIGHT);
		    $TOTALWEIGHT = node_exist(getArrayName($XPATH_TOTALVOLUME));

		     /*GET WEIGHTUNIT*/
		    $XPATH_WEIGHTUNIT= jsonPath($universal_shipment, $pathDataSource.".TotalWeightUnit.Code");
			$XPATH_WEIGHTUNIT = $parser->encode($XPATH_WEIGHTUNIT);
		    $WEIGHTUNIT = node_exist(getArrayName($XPATH_WEIGHTUNIT));

		     /*GET ETA*/
		    $XPATH_ETA= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.EstimatedArrival");
			$XPATH_ETA = $parser->encode($XPATH_ETA);
		    $ETA = node_exist(getArrayName($XPATH_ETA));

		     /*GET ETD*/
		    $XPATH_ETD= jsonPath($universal_shipment, $path_DataSourceTransport.".TransportLeg.EstimatedDeparture");
			$XPATH_ETD = $parser->encode($XPATH_ETD);
		    $ETD = node_exist(getArrayName($XPATH_ETD));

		    /*GET CONTAINER DETAILS*/
		    $XPATH_CONTAINERNUM = jsonPath($universal_shipment, $path_DataSourceContainer.".Container.ContainerNumber");
			$XPATH_CONTAINERNUM = $parser->encode($XPATH_CONTAINERNUM);
		    $CONTAINERNUM = node_exist(getArrayName($XPATH_CONTAINERNUM));

		    /*GET CONTAINER DETAILS*/
		    $container_array = array();  
		    $XPATH_CONTAINERTYPECODE = jsonPath($universal_shipment, $path_DataSourceContainer.".Container.ContainerType.Code");
			$XPATH_CONTAINERTYPECODE = $parser->encode($XPATH_CONTAINERTYPECODE);
		    $CONTAINERTYPECODE = node_exist(getArrayName($XPATH_CONTAINERTYPECODE));

		    /*GET CONTAINER DETAILS*/
		    $XPATH_CONTAINERCATCODE = jsonPath($universal_shipment, $path_DataSourceContainer.".Container.ContainerType.Category.Code");
			$XPATH_CONTAINERCATCODE = $parser->encode($XPATH_CONTAINERCATCODE);
		    $CONTAINERCATCODE = node_exist(getArrayName($XPATH_CONTAINERCATCODE));

		     /*GET CONTAINER DETAILS*/
		    $XPATH_CONTAINERDESC = jsonPath($universal_shipment, $path_DataSourceContainer.".Container.Description");
			$XPATH_CONTAINERDESC = $parser->encode($XPATH_CONTAINERDESC);
		    $CONTAINERDESC = node_exist(getArrayName($XPATH_CONTAINERDESC));

		    $container_array[] = array("ContainerNumber"=>$CONTAINERNUM,"ContainerTypeCode"=>$CONTAINERTYPECODE,"CategoryCode"=>$CONTAINERCATCODE,"ContainerDescription"=>$CONTAINERDESC);


		    /*GET MILESTONE COUNT*/
		    $MILESTONE = jsonPath($universal_shipment, $path_DataSourceMileStone.".Milestone");
			$MILESTONECTR = $MILESTONE;
				if ($MILESTONECTR != false) {
					$MILESTONECTR = jsonPath($universal_shipment, $path_DataSourceMileStone.".Milestone");
					$MILESTONECTR = count($MILESTONECTR[0]);
				}
				else
				{
					$MILESTONECTR = 0;
				}

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


		  /*FETCH MILESTONECOLLECTION*/		
		  $milestone_array = array();  
		  for ($a = 0; $a <= $MILESTONECTR-1; $a++) {
		  	$XPATH_ORDERMILESTONE_DESC = jsonPath($universal_shipment, $path_DataSourceMileStone.".Milestone[$a].Description");
			$XPATH_ORDERMILESTONE_DESC = $parser->encode($XPATH_ORDERMILESTONE_DESC);
		    $XPATH_ORDERMILESTONE_DESC = node_exist(getArrayName($XPATH_ORDERMILESTONE_DESC));

		    $XPATH_ORDERMILESTONE_EVENTCODE = jsonPath($universal_shipment, $path_DataSourceMileStone.".Milestone[$a].EventCode");
			$XPATH_ORDERMILESTONE_EVENTCODE = $parser->encode($XPATH_ORDERMILESTONE_EVENTCODE);
		    $XPATH_ORDERMILESTONE_EVENTCODE = node_exist(getArrayName($XPATH_ORDERMILESTONE_EVENTCODE));

		    $XPATH_ORDERMILESTONE_SEQ = jsonPath($universal_shipment, $path_DataSourceMileStone.".Milestone[$a].Sequence");
			$XPATH_ORDERMILESTONE_SEQ = $parser->encode($XPATH_ORDERMILESTONE_SEQ);
		    $XPATH_ORDERMILESTONE_SEQ = node_exist(getArrayName($XPATH_ORDERMILESTONE_SEQ));

		    $XPATH_ORDERMILESTONE_ACTUALDATE = jsonPath($universal_shipment, $path_DataSourceMileStone.".Milestone[$a].ActualDate");
			$XPATH_ORDERMILESTONE_ACTUALDATE = $parser->encode($XPATH_ORDERMILESTONE_ACTUALDATE);
		    $XPATH_ORDERMILESTONE_ACTUALDATE = node_exist(getArrayName($XPATH_ORDERMILESTONE_ACTUALDATE));

		    $XPATH_ORDERMILESTONE_CONREF = jsonPath($universal_shipment, $path_DataSourceMileStone.".Milestone[$a].ConditionReference");
			$XPATH_ORDERMILESTONE_CONREF = $parser->encode($XPATH_ORDERMILESTONE_CONREF);
		    $XPATH_ORDERMILESTONE_CONREF = node_exist(getArrayName($XPATH_ORDERMILESTONE_CONREF));

		    $XPATH_ORDERMILESTONE_CONTYPE = jsonPath($universal_shipment, $path_DataSourceMileStone.".Milestone[$a].ConditionType");
			$XPATH_ORDERMILESTONE_CONTYPE = $parser->encode($XPATH_ORDERMILESTONE_CONTYPE);
		    $XPATH_ORDERMILESTONE_CONTYPE = node_exist(getArrayName($XPATH_ORDERMILESTONE_CONTYPE));

		    $XPATH_ORDERMILESTONE_ESDATE = jsonPath($universal_shipment, $path_DataSourceMileStone.".Milestone[$a].EstimatedDate");
			$XPATH_ORDERMILESTONE_ESDATE = $parser->encode($XPATH_ORDERMILESTONE_ESDATE);
		    $XPATH_ORDERMILESTONE_ESDATE = node_exist(getArrayName($XPATH_ORDERMILESTONE_ESDATE));


		    $milestone_array[] = array("Description"=>$XPATH_ORDERMILESTONE_DESC,"EventCode"=>$XPATH_ORDERMILESTONE_EVENTCODE,"Sequence"=>$XPATH_ORDERMILESTONE_SEQ,"ActualDate"=>$XPATH_ORDERMILESTONE_ACTUALDATE,"ConditionReference"=>$XPATH_ORDERMILESTONE_CONREF,"ConditionType"=>$XPATH_ORDERMILESTONE_CONTYPE,"EstimatedDate"=>$XPATH_ORDERMILESTONE_ESDATE);
		  }


		  /*FETCH ORGADDRESS COLLECTION */
		  $orgaddress_array = array();  
		  for ($b = 0; $b <= $ORGADDRESSCTR-1; $b++) {
		  	$XPATH_ORDER_ADDRESSTYPE = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].AddressType");
			$XPATH_ORDER_ADDRESSTYPE = $parser->encode($XPATH_ORDER_ADDRESSTYPE);
		    $ORDER_ADDRESSTYPE = node_exist(getArrayName($XPATH_ORDER_ADDRESSTYPE));

		    if($ORDER_ADDRESSTYPE == "ConsigneeDocumentaryAddress" || $ORDER_ADDRESSTYPE == "ConsignorDocumentaryAddress"){
		  	$XPATH_ORDER_ORGCODE = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].OrganizationCode");
			$XPATH_ORDER_ORGCODE = $parser->encode($XPATH_ORDER_ORGCODE);
		    $XORDER_ORGCODE = node_exist(getArrayName($XPATH_ORDER_ORGCODE));

		    $XPATH_ORDER_ORGADDRESS = jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Address1");
			$XPATH_ORDER_ORGADDRESS = $parser->encode($XPATH_ORDER_ORGADDRESS);
		    $ORDER_ORGADDRESS = node_exist(str_replace("'","",getArrayName($XPATH_ORDER_ORGADDRESS)));

		    $XPATH_ORDER_ORGCOMPANY= jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].CompanyName");
			$XPATH_ORDER_ORGCOMPANY = $parser->encode($XPATH_ORDER_ORGCOMPANY);
		    $ORDER_ORGCOMPANY = node_exist(str_replace("'","",getArrayName($XPATH_ORDER_ORGCOMPANY)));

		    $orgaddress_array[] = array($ORDER_ADDRESSTYPE=>$XORDER_ORGCODE,"Address"=>$ORDER_ORGADDRESS,"CompanyName"=>$ORDER_ORGCOMPANY);
		    $orgaddress = json_decode(json_encode($orgaddress_array));

		    }    

		  }
		}
		   catch(Exception $e){
		  		echo 'Caught exception: ',  $e->getMessage(), "\n";
		  }

		  /* pass value fields*/
		  try{
  		  $ordernum = $ORDERNUMBER;
		  $buyer = $orgaddress[0]->ConsigneeDocumentaryAddress;
		  $seller = $orgaddress[1]->ConsignorDocumentaryAddress;
		  $arrival = $ETA;
		  $departure = $ETD;
		  $portloading = $POL;
		  $portdischarge = $POD;
		  $transmode = $TRANSPORTMODE;
		  $t_volume = $TOTALVOLUME;
		  $t_weight = $TOTALWEIGHT;
		  $t_weightunit = $WEIGHTUNIT;
		  $milestone = json_encode($milestone_array);
		  $organization = json_encode($orgaddress_array);
		  $container = json_encode($container_array);
		  $keyvalue = $KEY;
		  $order_status = $ORDERSTATUS;
		  $order_desc = $ORDERSTATDES;

		  $sql = "SELECT * FROM dbo.orders WHERE dbo.orders.order_number = '".$ordernum."' AND dbo.orders.user_id ='".$CLIENT_ID."'";
		  $qryOrder = sqlsrv_query($conn, $sql, array(), array( "Scrollable" => 'static'));
		  $row_count = sqlsrv_num_rows($qryOrder);

		  /* insert new value fields to orders*/
		  if($row_count<=0){
		  	 $sqlorderinsert = "INSERT INTO dbo.orders (user_id,order_number,buyer,seller,eta,etd,port_load,port_origin,trans_mode,total_volume,total_weight,weight_unit,milestone_dataset,organization_dataset,transportleg_dataset,container_dataset,data_key,status,status_desc) Values
		  	 ($CLIENT_ID,'".$ordernum."','".$buyer."','".$seller."','".$arrival."','".$departure."','".$portloading."','".$portdischarge."','".$transmode."',".$t_volume.",".$t_weight.",'".$t_weightunit."','".$milestone."','".$organization."','transportleg','".$container."','".$keyvalue."','".$order_status."','".$order_desc."')";
		  	$qryOrder = sqlsrv_query($conn, $sqlorderinsert, array(), array( "Scrollable" => 'static'));
		  }
		  else
		  {
		  	/* updates fields to existing orders*/
		  	 $sqlorderupdate="UPDATE dbo.orders
		  	SET user_id=$CLIENT_ID,order_number='$ordernum',buyer='$buyer',seller='$seller',eta='$arrival',etd='$departure',port_load='$portloading',port_origin='$portdischarge',trans_mode='$transmode',total_volume=$t_volume,total_weight=$t_weight,weight_unit='$t_weightunit',milestone_dataset='$milestone',organization_dataset='$organization',transportleg_dataset='transportleg',container_dataset='$organization',data_key='$keyvalue',status='$order_status',status_desc='$order_desc'
		  	WHERE order_number = '$ordernum' AND user_id = '$CLIENT_ID'";
		  	$qryOrder = sqlsrv_query($conn, $sqlorderupdate, array(), array( "Scrollable" => 'static'));

		  	}

		  	$destination_path = "E:/A2BFREIGHT_MANAGER/$client_email/CW_XML/Orders/SUCCESS/";						
			if(!file_exists($destination_path.$filename)){
			rename($filename, $destination_path . pathinfo($filename, PATHINFO_BASENAME));
			}
		  }
		  catch(Exception $e){
		  		echo 'Caught exception: ',  $e->getMessage(), "\n";
		  	}
		}
	}
?>