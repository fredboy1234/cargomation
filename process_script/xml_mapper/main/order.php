<?php 
if(isset($GLOBALS['client_email'])){
$user = $client_email;
$CLIENT_ID = $_GET['user_id'];
$myarray_order = glob("E:/A2BFREIGHT_MANAGER/$user/CW_XML/CW_ORDERS/IN/*.xml");
		usort($myarray_order, fn($a, $b) => filemtime($a) - filemtime($b));
		foreach ($myarray_order as $filename) {
		try{
			$pack_array = array(); 
			$parser = new __Services_JSON(SERVICES_JSON_LOOSE_TYPE);
			$myxmlfilecontent = file_get_contents($filename);
			$xml = simplexml_load_string($myxmlfilecontent);
			$universalshipment = json_encode($xml, JSON_PRETTY_PRINT);
			$universal_shipment = json_decode($universalshipment, true);

			$pathDataSource = "$.Body.UniversalShipment.Shipment";
			$pathDataSourceContext = "$.Body.UniversalShipment.Shipment.DataContext.DataSourceCollection";
			$path_DataSourceOrder ="$.Body.UniversalShipment.Shipment.Order";
			$path_DataSourceMileStone ="$.Body.UniversalShipment.Shipment.MilestoneCollection";
			$path_DataSourceOrganizationAddress="$.Body.UniversalShipment.Shipment.OrganizationAddressCollection";
			$path_DateSourceOderLineCollection = "$.Body.UniversalShipment.Shipment.Order.OrderLineCollection";
			$path_DataSourceDateCollection ="$.Body.UniversalShipment.Shipment.DateCollection";
			$path_DataSourceTransport="$.Body.UniversalShipment.Shipment.TransportLegCollection";
			$path_DataSourceContainer="$.Body.UniversalShipment.Shipment.ContainerCollection";

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
		    $XPATH_POL = jsonPath($universal_shipment, $pathDataSource.".PortOfLoading.Name");
			$XPATH_POL = $parser->encode($XPATH_POL);
		    $POL = node_exist(getArrayName($XPATH_POL));

		     /*GET PORTDISCHARGE*/
		    $XPATH_POD = jsonPath($universal_shipment, $pathDataSource.".PortOfDischarge.Name");
			$XPATH_POD = $parser->encode($XPATH_POD);
		    $POD = node_exist(getArrayName($XPATH_POD));

		     /*GET PACKOUTERPACK*/
		    $XPATH_OUTERPACK = jsonPath($universal_shipment, $pathDataSource.".OuterPacks");
			$XPATH_OUTERPACK = $parser->encode($XPATH_OUTERPACK);
		    $OUTERPACK = node_exist(getArrayName($XPATH_OUTERPACK));

		     /*GET PACKTYPECODE*/
		    $XPATH_PACKTYPECODE = jsonPath($universal_shipment, $pathDataSource.".OuterPacksPackageType.Code");
			$XPATH_PACKTYPECODE = $parser->encode($XPATH_PACKTYPECODE);
		    $PACKTYPECODE = node_exist(getArrayName($XPATH_PACKTYPECODE));

		      /*GET PACKTYPEDESC*/
		    $XPATH_PACKTYPEDESC = jsonPath($universal_shipment, $pathDataSource.".OuterPacksPackageType.Description");
			$XPATH_PACKTYPEDESC = $parser->encode($XPATH_PACKTYPEDESC);
		    $PACKTYPEDESC = node_exist(getArrayName($XPATH_PACKTYPEDESC));
		    $pack_array[] = array("Code"=>$PACKTYPECODE,"Description"=>$PACKTYPEDESC,"OuterPack"=>$OUTERPACK);
		    $pack_array = json_encode($pack_array);

		     /*GET TRANSPORTMODE*/
		    $XPATH_TRANSPORTMODE = jsonPath($universal_shipment, $pathDataSource.".TransportMode.Code");
			$XPATH_TRANSPORTMODE = $parser->encode($XPATH_TRANSPORTMODE);
		    $TRANSPORTMODE = node_exist(getArrayName($XPATH_TRANSPORTMODE));

		     /*GET TOTAL VOLUME*/
		    $XPATH_TOTALVOLUME = jsonPath($universal_shipment, $pathDataSource.".TotalVolume");
			$XPATH_TOTALVOLUME = $parser->encode($XPATH_TOTALVOLUME);
		    $TOTALVOLUME = node_exist(getArrayName($XPATH_TOTALVOLUME));

		    /*GET TOTAL VOLUME unit*/
		    $XPATH_TOTALVOLUME_UNT = jsonPath($universal_shipment, $pathDataSource.".TotalVolumeUnit.Code");
			$XPATH_TOTALVOLUME_UNT = $parser->encode($XPATH_TOTALVOLUME_UNT);
		    $TOTALVOLUME_UNT = node_exist(getArrayName($XPATH_TOTALVOLUME_UNT));

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

			   /*GET DATECOLLECTION COUNT*/
		    $DATECOLLECTION = jsonPath($universal_shipment, $path_DataSourceDateCollection.".Date");
			$DATECOLLECTIONCTR = $DATECOLLECTION;
				if ($DATECOLLECTIONCTR != false) {
					$DATECOLLECTIONCTR = jsonPath($universal_shipment, $path_DataSourceDateCollection.".Date");
					$DATECOLLECTIONCTR = count($DATECOLLECTIONCTR[0]);
				}
				else
				{
					$DATECOLLECTIONCTR = 0;
				}

				  /*GET ORDERLINE COUNT*/
		    $ORDERCOLLECTION = jsonPath($universal_shipment, $path_DateSourceOderLineCollection.".OrderLine");
			$ORDERCOLLECTIONCTR = $ORDERCOLLECTION;
				if ($ORDERCOLLECTIONCTR != false) {
					$ORDERCOLLECTIONCTR = jsonPath($universal_shipment, $path_DateSourceOderLineCollection.".OrderLine");
					$ORDERCOLLECTIONCTR = count($ORDERCOLLECTIONCTR[0]);
				}
				else
				{
					$ORDERCOLLECTIONCTR = 0;
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

		    if($ORDER_ADDRESSTYPE == "ConsigneeDocumentaryAddress" || $ORDER_ADDRESSTYPE == "ConsignorDocumentaryAddress" ){
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
		    elseif($ORDER_ADDRESSTYPE == "GoodsAvailableAt"){
		    $XPATH_ORDER_ORGPORT= jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Port.Name");
			$XPATH_ORDER_ORGPORT = $parser->encode($XPATH_ORDER_ORGPORT);
		    $ORDER_ORGPORT = node_exist(str_replace("'","",getArrayName($XPATH_ORDER_ORGPORT)));

		    $XPATH_ORDER_ORGCTRY= jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Country.Code");
			$XPATH_ORDER_ORGCTRY = $parser->encode($XPATH_ORDER_ORGCTRY);
		    $ORDER_ORGCTRY = node_exist(str_replace("'","",getArrayName($XPATH_ORDER_ORGCTRY)));

		    $GOODS_ORIGIN = $ORDER_ORGPORT.",".$ORDER_ORGCTRY;
		    }elseif($ORDER_ADDRESSTYPE == "GoodsDeliveredTo"){
		    $XPATH_ORDER_ORGPORT= jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Port.Name");
			$XPATH_ORDER_ORGPORT = $parser->encode($XPATH_ORDER_ORGPORT);
		    $ORDER_ORGPORT = node_exist(str_replace("'","",getArrayName($XPATH_ORDER_ORGPORT)));

		    $XPATH_ORDER_ORGCTRY= jsonPath($universal_shipment, $path_DataSourceOrganizationAddress.".OrganizationAddress[$b].Country.Code");
			$XPATH_ORDER_ORGCTRY = $parser->encode($XPATH_ORDER_ORGCTRY);
		    $ORDER_ORGCTRY = node_exist(str_replace("'","",getArrayName($XPATH_ORDER_ORGCTRY)));	
		    $GOODS_DES = $ORDER_ORGPORT.",".$ORDER_ORGCTRY;
		    }
		  }
		  /*FETCH DATE COLLECTION */
		 // $orgaddress_array = array();  
		  $ORDER_DATE = "";
		  for ($c = 0; $c <= $DATECOLLECTIONCTR-1; $c++) {
		  	$DATE_TYPE = jsonPath($universal_shipment, $path_DataSourceDateCollection.".Date[$c].Type");
			$DATE_TYPE = $parser->encode($DATE_TYPE);
		    $DTYPE = node_exist(getArrayName($DATE_TYPE));

		    if($DTYPE == "OrderDate"){
		  	$XPATH_ORDER_DATE = jsonPath($universal_shipment, $path_DataSourceDateCollection.".Date[$c].Value");
			$XPATH_ORDER_DATE = $parser->encode($XPATH_ORDER_DATE);
		    $ORDER_DATE = node_exist(getArrayName($XPATH_ORDER_DATE));
		     }    
		  }

		  /*FETCH ORDERLINE COLLECTION */
		  $order_line = array(); 
		  if($ORDERCOLLECTIONCTR <= 5){
		  for ($d = 0; $d <= $ORDERCOLLECTIONCTR-1; $d++) {
		  	$LINE_NUMBER = jsonPath($universal_shipment, $path_DataSourceDateCollection.".OrderLine[$d].LineNumber");
			$LINE_NUMBER = $parser->encode($LINE_NUMBER);
		    $LINE_NUMBER = node_exist(getArrayName($LINE_NUMBER));  

		    $PKG_QTY = jsonPath($universal_shipment, $path_DateSourceOderLineCollection.".OrderLine[$d].PackageQty");
			$PKG_QTY = $parser->encode($PKG_QTY);
		    $PKG_QTY = node_exist(getArrayName($PKG_QTY));  

		    $ORD_QTY = jsonPath($universal_shipment, $path_DateSourceOderLineCollection.".OrderLine[$d].OrderedQty");
			$ORD_QTY = $parser->encode($ORD_QTY);
		    $ORD_QTY = node_exist(getArrayName($ORD_QTY)); 

		    $ORD_QTYCODE = jsonPath($universal_shipment, $path_DateSourceOderLineCollection.".OrderLine[$d].OrderedQtyUnit.Code");
			$ORD_QTYCODE = $parser->encode($ORD_QTYCODE);
		    $ORD_QTYCODE = node_exist(getArrayName($ORD_QTYCODE));   

		    $ORD_PRODUCTCODE= jsonPath($universal_shipment, $path_DateSourceOderLineCollection.".OrderLine[$d].Product.Code");
			$ORD_PRODUCTCODE = $parser->encode($ORD_PRODUCTCODE);
		    $PRODUCTCODE = node_exist(getArrayName($ORD_PRODUCTCODE));

		    $ORD_PRODUCTDESC= jsonPath($universal_shipment, $path_DateSourceOderLineCollection.".OrderLine[$d].Product.Name");
			$ORD_PRODUCTDESC = $parser->encode($ORD_PRODUCTDESC);
		    $PRODUCTDESC = node_exist(getArrayName($ORD_PRODUCTDESC));
		     $order_line[] = array("LineNumber"=>$LINE_NUMBER,"PackageQty"=>$PKG_QTY,"OrderedQty"=>$ORD_QTY,"OrderedQtyUnitCode"=>$ORD_QTYCODE,"ProductCode"=>$PRODUCTCODE,"ProductName"=>$PRODUCTDESC,"Volume"=>$TOTALVOLUME." ".$TOTALVOLUME_UNT);
		  	 
		  	 }
		  }else{
		  	$LINE_NUMBER = jsonPath($universal_shipment, $path_DateSourceOderLineCollection.".OrderLine.LineNumber");
			$LINE_NUMBER = $parser->encode($LINE_NUMBER);
		    $LINE_NUMBER = node_exist(getArrayName($LINE_NUMBER));  

		    $PKG_QTY = jsonPath($universal_shipment, $path_DateSourceOderLineCollection.".OrderLine.PackageQty");
			$PKG_QTY = $parser->encode($PKG_QTY);
		    $PKG_QTY = node_exist(getArrayName($PKG_QTY));  

		    $ORD_QTY = jsonPath($universal_shipment, $path_DateSourceOderLineCollection.".OrderLine.OrderedQty");
			$ORD_QTY = $parser->encode($ORD_QTY);
		    $ORD_QTY = node_exist(getArrayName($ORD_QTY)); 

		    $ORD_QTYCODE = jsonPath($universal_shipment, $path_DateSourceOderLineCollection.".OrderLine.OrderedQtyUnit.Code");
			$ORD_QTYCODE = $parser->encode($ORD_QTYCODE);
		    $ORD_QTYCODE = node_exist(getArrayName($ORD_QTYCODE));   

		    $ORD_PRODUCTCODE= jsonPath($universal_shipment, $path_DateSourceOderLineCollection.".OrderLine.Product.Code");
			$ORD_PRODUCTCODE = $parser->encode($ORD_PRODUCTCODE);
		    $PRODUCTCODE = node_exist(getArrayName($ORD_PRODUCTCODE));  

		    $ORD_PRODUCTDESC= jsonPath($universal_shipment, $path_DateSourceOderLineCollection.".OrderLine.Product.Description");
			$ORD_PRODUCTDESC = $parser->encode($ORD_PRODUCTDESC);
		    $PRODUCTDESC = node_exist(getArrayName($ORD_PRODUCTDESC));  
		    $order_line[] = array("LineNumber"=>$LINE_NUMBER,"PackageQty"=>$PKG_QTY,"OrderedQty"=>$ORD_QTY,"OrderedQtyUnitCode"=>$ORD_QTYCODE,"ProductCode"=>$PRODUCTCODE,"ProductName"=>$PRODUCTDESC,"Volume"=>$TOTALVOLUME." ".$TOTALVOLUME_UNT);
		 }

	}
	catch(Exception $e){
	echo 'Caught exception: ',  $e->getMessage(), "\n";
 }
		  /* pass value fields*/
		  try{
  		  $ordernum = $ORDERNUMBER;
  		  $goods_origin = $GOODS_ORIGIN;
		  $goods_des = $GOODS_DES;
		  //$buyer = $orgaddress[2]->ConsigneeDocumentaryAddress;
		  //$seller = $orgaddress[3]->ConsignorDocumentaryAddress;
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
		  $order_line = json_encode($order_line);
		  $keyvalue = $KEY;
		  $order_status = $ORDERSTATUS;
		  $order_desc = $ORDERSTATDES;
		  $order_date = $ORDER_DATE;

		  $sql = "SELECT TOP 1 dbo.orders.order_number FROM dbo.orders WHERE dbo.orders.order_number = '".$ordernum."' AND dbo.orders.user_id ='".$CLIENT_ID."'";
		  $qryOrder = sqlsrv_query($conn, $sql, array(), array( "Scrollable" => 'static'));
		  $row_count = sqlsrv_num_rows($qryOrder);
	

		  /* insert new value fields to orders*/
		  if($row_count<=0){
		  	 $sqlorderinsert = "INSERT INTO dbo.orders (user_id,order_number,eta,etd,port_load,port_origin,trans_mode,total_volume,total_weight,weight_unit,milestone_dataset,organization_dataset,transportleg_dataset,container_dataset,data_key,status,status_desc,order_date,pack_info,order_line,goods_origin,goods_destination) Values
		  	 ($CLIENT_ID,'".$ordernum."','".$arrival."','".$departure."','".$portloading."','".$portdischarge."','".$transmode."',".$t_volume.",".$t_weight.",'".$t_weightunit."','".$milestone."','".$organization."','transportleg','".$container."','".$keyvalue."','".$order_status."','".$order_desc."','".$order_date."','".$pack_array."','".$order_line."','".$goods_origin."','".$goods_des."')";
		  	$qryOrder = sqlsrv_query($conn, $sqlorderinsert, array(), array( "Scrollable" => 'static'));
		  }
		  else
		  {
		  	/* updates fields to existing orders*/
		  	 $sqlorderupdate="UPDATE dbo.orders
		  	SET user_id=$CLIENT_ID,order_number='$ordernum',eta='$arrival',etd='$departure',port_load='$portloading',port_origin='$portdischarge',trans_mode='$transmode',total_volume=$t_volume,total_weight=$t_weight,weight_unit='$t_weightunit',milestone_dataset='$milestone',organization_dataset='$organization',transportleg_dataset='transportleg',container_dataset='$organization',data_key='$keyvalue',status='$order_status',status_desc='$order_desc',order_date='$order_date',pack_info='$pack_array',order_line='$order_line',goods_origin='$goods_origin',goods_destination='$goods_destination'
		  	WHERE order_number = '$ordernum' AND user_id = '$CLIENT_ID'";
		  	$qryOrder = sqlsrv_query($conn, $sqlorderupdate, array(), array( "Scrollable" => 'static'));

		  }
		  	$destination_path = "E:/A2BFREIGHT_MANAGER/$client_email/CW_XML/CW_ORDERS/SUCCESS/";						
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
