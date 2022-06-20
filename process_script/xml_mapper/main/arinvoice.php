<?php 
if(isset($GLOBALS['client_email'])){
$user = $client_email;
$CLIENT_ID = $_GET['user_id'];
$myarray_order = glob("E:/A2BFREIGHT_MANAGER/$user/CW_XML/CW_AR_INVOICE/IN/*.xml");
		usort($myarray_order, fn($a, $b) => filemtime($a) - filemtime($b));
		foreach ($myarray_order as $filename) {

		try{
			$parser = new __Services_JSON(SERVICES_JSON_LOOSE_TYPE);
			$myxmlfilecontent = file_get_contents($filename);
			$xml = simplexml_load_string($myxmlfilecontent);
			$universalshipment = json_encode($xml, JSON_PRETTY_PRINT);
			$universal_shipment = json_decode($universalshipment, true);

			$path_TransactionInfo = "$.Body.UniversalTransaction.TransactionInfo";
			$path_Chargeline = "$.Body.UniversalTransaction.TransactionInfo.ShipmentCollection.Shipment.JobCosting.ChargeLineCollection";
			$path_ChargelineConsol = "$.Body.UniversalTransaction.TransactionInfo.ShipmentCollection.Shipment.SubShipmentCollection.SubShipment.JobCosting.ChargeLineCollection";
			$path_JobCosting = "$.Body.UniversalTransaction.TransactionInfo.ShipmentCollection.Shipment.SubShipmentCollection.SubShipment.JobCosting";
		
			
			/*Check if there's attached consol change xpath */
			$Code= jsonPath($universal_shipment, $path_JobCosting.".Branch.Code");
			$Code = $parser->encode($Code);

			if($Code == 'false'){ $xpath = $path_Chargeline; } else{ $xpath = $path_ChargelineConsol; }

			/*GET Key DETAILS*/
			$ShipmentKey= jsonPath($universal_shipment, $path_TransactionInfo.".Job.Key");
			$ShipmentKey = $parser->encode($ShipmentKey);
		    $ShipmentKey = node_exist(getArrayName($ShipmentKey));

		    /*GET JobInvoiceNumber DETAILS*/
			$JobInvoiceNumber= jsonPath($universal_shipment, $path_TransactionInfo.".JobInvoiceNumber");
			$JobInvoiceNumber = $parser->encode($JobInvoiceNumber);
			$JobInvoiceNumber = node_exist(getArrayName($JobInvoiceNumber));

			/*GET TransactionNumber DETAILS*/
			$TransactionNumber= jsonPath($universal_shipment, $path_TransactionInfo.".Number");
			$TransactionNumber = $parser->encode($TransactionNumber);
			$TransactionNumber = node_exist(getArrayName($TransactionNumber));

			/*GET PostDate DETAILS*/
			$PostDate= jsonPath($universal_shipment, $path_TransactionInfo.".PostDate");
			$PostDate = $parser->encode($PostDate);
			$PostDate = node_exist(getArrayName($PostDate));

			/*GET TransactionDate / Invoice Date DETAILS*/
			$InvoiceDate= jsonPath($universal_shipment, $path_TransactionInfo.".TransactionDate");
			$InvoiceDate = $parser->encode($InvoiceDate);
			$InvoiceDate = node_exist(getArrayName($InvoiceDate));

			/*GET InvoiceAmount DETAILS*/
			$InvoiceAmount= jsonPath($universal_shipment, $path_TransactionInfo.".LocalTotal");
			$InvoiceAmount = $parser->encode($InvoiceAmount);
			$InvoiceAmount = node_exist(getArrayName($InvoiceAmount));

			/*GET OutstandingAmount DETAILS*/
			$OutstandingAmount= jsonPath($universal_shipment, $path_TransactionInfo.".OutstandingAmount");
			$OutstandingAmount = $parser->encode($OutstandingAmount);
			$OutstandingAmount = node_exist(getArrayName($OutstandingAmount));

			  /*GET if 1 COUNT*/
		    $Chargectr = jsonPath($universal_shipment, $xpath.".ChargeLine.SellPostedTransactionNumber");
			$ChargeBoolean = $parser->encode($Chargectr);

			  /*GET ChargeLine COUNT*/
		    $Chargeline = jsonPath($universal_shipment, $xpath.".ChargeLine[*]");
			$ChargelineCTR = $Chargeline;
			
				if ($ChargeBoolean == 'false') {
					$ChargelineCTR = jsonPath($universal_shipment, $xpath.".ChargeLine[*]");
					$ChargelineCTR = count($ChargelineCTR);
					
				}
				else
				{
					$ChargelineCTR = 1;
					$ChargeLineArr = ".ChargeLine.";

				}

            for ($a = 0; $a <= $ChargelineCTR-1; $a++) {
            	if ($ChargeBoolean == 'false'){ 
            		$ChargeLineArr = ".ChargeLine[$a]."; 
            	}

            	$SellPostedTransactionNumber = jsonPath($universal_shipment, $xpath.$ChargeLineArr."SellPostedTransactionNumber");
				$SellPostedTransactionNumber = $parser->encode($SellPostedTransactionNumber);
		    	$SellPostedTransactionNumber = node_exist(getArrayName($SellPostedTransactionNumber));

		    	if(strval($TransactionNumber) == strval($SellPostedTransactionNumber)){

		    		$SellInvoiceType = jsonPath($universal_shipment, $xpath.$ChargeLineArr."SellInvoiceType");
					$SellInvoiceType = $parser->encode($SellInvoiceType);
			    	$SellInvoiceType = node_exist(getArrayName($SellInvoiceType));

			    	$Debtor = jsonPath($universal_shipment, $xpath.$ChargeLineArr."Debtor.Key");
					$Debtor = $parser->encode($Debtor);
			    	$Debtor = node_exist(getArrayName($Debtor));

			    	$FullyPaidDate = jsonPath($universal_shipment, $xpath.$ChargeLineArr."SellPostedTransaction.FullyPaidDate");
					$FullyPaidDate = $parser->encode($FullyPaidDate);
			    	$FullyPaidDate = node_exist(getArrayName($FullyPaidDate));

			    	break;
		    	}
		    	else{
		    		continue;
		    	}
            }

            try{
            /*check if invoice exist*/
            	 $sql = "SELECT * FROM dbo.shipment_arinvoice WHERE dbo.shipment_arinvoice.user_id = '".$CLIENT_ID."' AND dbo.shipment_arinvoice.shipment_num ='".$ShipmentKey."' AND dbo.shipment_arinvoice.job_invoicenum ='".$JobInvoiceNumber."' AND dbo.shipment_arinvoice.transaction_num ='".$TransactionNumber."'";
				 $qryArInvoice = sqlsrv_query($conn, $sql, array(), array( "Scrollable" => 'static'));
				 $ctr_qryArInvoice = sqlsrv_num_rows($qryArInvoice);

            /*if not exist insert AR Details*/
				 if($ctr_qryArInvoice<=0){
				  	 $sqlarinsert = "INSERT INTO dbo.shipment_arinvoice (user_id,shipment_num,job_invoicenum,transaction_num,inv_type,debtor,post_date,invoice_date,fullypaid_date,invoice_amount,outstanding_amount) Values
				  	 ($CLIENT_ID,'".$ShipmentKey."','".$JobInvoiceNumber."','".$TransactionNumber."','".$SellInvoiceType."','".$Debtor."','".$PostDate."','".$InvoiceDate."','".$FullyPaidDate."',".$InvoiceAmount.",".$OutstandingAmount.")";
				  	$qryOrder = sqlsrv_query($conn, $sqlarinsert, array(), array( "Scrollable" => 'static'));
				  }
				  else{
				  	/* updates fields to existing ar invoice details*/
				  	 $sqlarupdate="UPDATE dbo.shipment_arinvoice
				  	SET inv_type='$SellInvoiceType',debtor='$Debtor',post_date='$PostDate',invoice_date='$InvoiceDate',fullypaid_date='$FullyPaidDate',invoice_amount=$InvoiceAmount,outstanding_amount=$OutstandingAmount
				  	WHERE dbo.shipment_arinvoice.user_id = '".$CLIENT_ID."' AND dbo.shipment_arinvoice.shipment_num ='".$ShipmentKey."' AND dbo.shipment_arinvoice.job_invoicenum ='".$JobInvoiceNumber."' AND dbo.shipment_arinvoice.transaction_num ='".$TransactionNumber."'";
				  	$qryOrder = sqlsrv_query($conn, $sqlarupdate, array(), array( "Scrollable" => 'static'));

		  	}

		  	$destination_path = "E:/A2BFREIGHT_MANAGER/$client_email/CW_XML/CW_AR_INVOICE/SUCCESS/";						
			if(!file_exists($destination_path.$filename)){
			rename($filename, $destination_path . pathinfo($filename, PATHINFO_BASENAME));
			}

            }
            catch(Exception $e){
		  		echo 'Caught exception: ',  $e->getMessage(), "\n";
		  }
	
		}
		   catch(Exception $e){
		  		echo 'Error Caught exception: ',  $e->getMessage(), "\n";
		  	}
		}
	}
?>
