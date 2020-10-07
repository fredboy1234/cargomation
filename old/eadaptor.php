<?php
require_once('connection.php');
require_once('json.php');      // JSON parser
require_once('jsonpath-0.8.1.php');      // JSON parser

header('Content-Type: text/html');
$curl = curl_init();
$curl_ = curl_init();

if(isset($_POST['shipid'])){
	$shipD = $_POST['shipid'];
}
else{
	$shipD = "";
}

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://a2bprdservices.wisegrid.net/eAdaptor",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>
  
  "<UniversalShipmentRequest xmlns=\"http://www.cargowise.com/Schemas/Universal/2011/11\" version=\"1.1\">\r\n
  <ShipmentRequest>\r\n
  <DataContext>\r\n
  <DataTargetCollection>\r\n
  <DataTarget>\r\n
  <Type>ForwardingShipment</Type>\r\n
  <Key>".$shipD."</Key>\r\n
  </DataTarget>\r\n
  </DataTargetCollection>\r\n
  <Company>\r\n
  <Code>SYD</Code>\r\n
  </Company>\r\n
  <EnterpriseID>A2B</EnterpriseID>\r\n
  <ServerID>PRD</ServerID>\r\n
  </DataContext>\r\n
  </ShipmentRequest>\r\n
  </UniversalShipmentRequest>",
  
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic QTJCOkh3N20zWGhT",
    "Content-Type: application/xml",
    "Cookie: WEBSVC=f50e2886473c750f"
  ),
));

curl_setopt_array($curl_, array(
  CURLOPT_URL => "https://a2bprdservices.wisegrid.net/eAdaptor",
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
  <Key>".$shipD."</Key>\r\n
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
  ),
));

function getArrayName($val){
	
	return str_replace(array('["', '"]','[',']'), array("","","",""), $val);
}

function Base64_Decoder($val , $valName){
$b64 = str_replace(array('["', '"]'), array("", ""), $val);
file_put_contents('E:/GLOBAL_MANAGER/GLOBAL_FILES/'.$valName, base64_decode($b64));
}

curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($curl);
$response1 = curl_exec($curl_);
curl_close($curl);
curl_close($curl_);

$xml = simplexml_load_string($response);
$xml_ = simplexml_load_string($response1);
$json_universalevent = json_encode($xml, JSON_PRETTY_PRINT);
$json_documentrequest = json_encode($xml_, JSON_PRETTY_PRINT);
$json_xpath = json_decode($json_universalevent, true);
$json_xpathdoc = json_decode($json_documentrequest, true);
$parser = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
$ctr_file_1 = 1;
$ctr_file_2 = 0;

?>

<html>
<head>
<style>
html{
	font-size:12pt;
	font-family:courier;
}
input[type=text], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

div {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}

.container {
  max-width: 70%;  
  margin: 40px auto;
}


</style>
</head>


<body>

<div>
  <form method="POST" action="">
    <label for="fname">SHIPMENT NUMBER</label>
    <input type="text" id="shipid" name="shipid" placeholder="Shipment Number...">
    <input type="submit" value="Submit">
  </form>
</div>

<?php 

if ($shipD  != ""){
	echo "<div class='container'>";
    echo"<h2>SHIPMENT DETAILS</h2>";
	echo"<hr class='hr-text' data-content='OR'>";
	
	
$OrganizationAddress = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress");
$OrganizationAddress_ctr = $OrganizationAddress;

if($OrganizationAddress_ctr != false){
$OrganizationAddress = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress");
$OrganizationAddress_ctr = count($OrganizationAddress[0]);
}
else{
$OrganizationAddress_ctr = $OrganizationAddress;
}


//GET WAYBLL NUMBER
$XPATH_WAYBILLNUMBER = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.WayBillNumber");
$WAYBILLNUMBER = $parser->encode($XPATH_WAYBILLNUMBER);


//GET HOUSEBILL NUMBER
$XPATH_HOUSEWAYBILLNUMBER = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.WayBillNumber");
$HOUSEWAYBILLNUMBER = $parser->encode($XPATH_HOUSEWAYBILLNUMBER);

//GET SHIPMENT NUMBER
$XPATH_SHIPMENTKEY = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.DataContext.DataSourceCollection.DataSource[1].Key");
$SHIPMENTKEY  = $parser->encode($XPATH_SHIPMENTKEY);

//GET CONSOLNUMBER
$XPATH_CONSOLNUMBER = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.DataContext.DataSourceCollection.DataSource[0].Key");
$CONSOLNUMBER  = $parser->encode($XPATH_CONSOLNUMBER);


//GET CONTAINER COUNT
$XPATH_CONTAINERCOUNT = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.ContainerCount");
$CONTAINERCOUNT  = $parser->encode($XPATH_CONTAINERCOUNT);
$CONTAINERctr = getArrayName($CONTAINERCOUNT);

//GET VESSEL NAME
$XPATH_VESSELNAME = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.VesselName");
$VESSELNAME  = $parser->encode($XPATH_VESSELNAME);

//GET VOYAGE#
$XPATH_VOYAGEFLIGHTNO = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.VoyageFlightNo");
$VOYAGEFLIGHTNO  = $parser->encode($XPATH_VOYAGEFLIGHTNO);

//GET VESSELLOYDSIMO
$XPATH_VESSELLOYDSIMO = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.TransportLegCollection.TransportLeg.VesselLloydsIMO");
$VESSELLOYDSIMO  = $parser->encode($XPATH_VESSELLOYDSIMO);

//GET TRANSPORT MODE
$XPATH_TRANSMODE = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.TransportLegCollection.TransportLeg.TransportMode");
$TRANSMODE  = $parser->encode($XPATH_TRANSMODE);

//GET PLACEOFDELIVERY
$XPATH_PLACEOFDELIVERY= jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.PlaceOfDelivery.Name");
$PLACEOFDELIVERY  = $parser->encode($XPATH_PLACEOFDELIVERY);

//GET PLACEOFRECEIPT
$XPATH_PLACEOFRECEIPT = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.PlaceOfReceipt.Name");
$PLACEOFRECEIPT  = $parser->encode($XPATH_PLACEOFRECEIPT);

//GET GOODSDECRIPTION
$XPATH_GOODSDECRIPTION = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.GoodsDescription");
$GOODSDECRIPTION  = $parser->encode($XPATH_GOODSDECRIPTION);


echo "<b>Shipment Number:</b> ".$SHIPMENTKEY = getArrayName($SHIPMENTKEY);
echo "<br>";
echo "<b>Consol Number:</b> ".$CONSOLNUMBER = getArrayName($CONSOLNUMBER);
echo "<br>";
echo "<b>WayBillNumber:</b> ".$WAYBILLNUMBER = getArrayName($WAYBILLNUMBER);
echo "<br>";
echo "<b>House Waybill:</b> ".$WAYBILLNUMBER = getArrayName($HOUSEWAYBILLNUMBER);
echo "<br>";
echo "<b>Vessel Name:</b> ".$VESSELNAME = getArrayName($VESSELNAME);
echo "<br>";
echo "<b>Voyage No:</b> ".$VOYAGEFLIGHTNO = getArrayName($VOYAGEFLIGHTNO);
echo "<br>";
echo "<b>VesselLloydsIMO:</b> ".$VESSELLOYDSIMO = getArrayName($VESSELLOYDSIMO);
echo "<br>";
echo "<b>Transport Mode:</b> ".$TRANSMODE = getArrayName($TRANSMODE);
echo "<br>";
echo "<b>Place of Delivery:</b> ".$PLACEOFDELIVERY = getArrayName($PLACEOFDELIVERY);
echo "<br>";
echo "<b>Place of Receipt:</b> ".$PLACEOFRECEIPT = getArrayName($PLACEOFRECEIPT);
echo "<br>";
echo "<b>Goods Description:</b> ".$GOODSDECRIPTION = getArrayName($GOODSDECRIPTION);
echo "<br>";


echo "<b>Container Number(s):</b><br>";
for($c = 0;$c <= $CONTAINERctr - 1; $c++){
	$XPATH_CONTAINERNUMBER = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.ContainerCollection.Container[$c].ContainerNumber");		
	$CONTAINERNUMBER  = $parser->encode($XPATH_CONTAINERNUMBER);
	echo getArrayName($CONTAINERNUMBER);
	echo "<br>";
}
for($a=0;$a<= $OrganizationAddress_ctr - 1;$a++){
	
	$XPATH_AddressType = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].AddressType");		
	$AddressType  = $parser->encode($XPATH_AddressType);
	
	$XPATH_AddressType1 = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].Address1");		
	$AddressType1  = $parser->encode($XPATH_AddressType1);
	
	$XPATH_AddressType2 = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].Address2");		
	$AddressType2  = $parser->encode($XPATH_AddressType2);
	
	$XPATH_State = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].State");		
	$State  = $parser->encode($XPATH_State);
	
	$XPATH_Postcode = jsonPath($json_xpath, "$.Data.UniversalShipment.Shipment.SubShipmentCollection.SubShipment.OrganizationAddressCollection.OrganizationAddress[$a].Postcode");		
	$Postcode  = $parser->encode($XPATH_Postcode);
	
	$AddressType = getArrayName($AddressType);
	$AddressType1 = getArrayName($AddressType1);
	$AddressType2 = getArrayName($AddressType2);
	$State = getArrayName($State);
	$Postcode = getArrayName($Postcode);
	echo "<b>".$AddressType."</b>: ".$AddressType1." ,".$AddressType2." ,".$State." ,".$Postcode."<br>";
	
	
}

for ($x = 0; $x <= 1; $x++) {
  $xpath_AttachedCountSingle = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument.FileName");
  $xpath_AttachedB64 = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument.ImageData");
  $SingleAttach_ctr = $parser->encode($xpath_AttachedCountSingle);
  $SingleAttach_ctrb64 = $parser->encode($xpath_AttachedB64);
  $ctr_1 = getArrayName($SingleAttach_ctr);
  $ctr_b64 = getArrayName($SingleAttach_ctrb64);
  
  if(strval($SingleAttach_ctr) == 'false'){
	  $ctr_file_1 = 0;
	 break;
  }
  else{ 
	  $ctr_file_1++;
	  echo"<b><br>Attached File(s): </b><br>";
	  echo "<a target='_blank' href='http://a2bfreighthub.com/xpath_files/$ctr_1'>".$ctr_1."</a>"; 
	  Base64_Decoder($ctr_b64,$ctr_1);
	  echo "<br>";
	 break;
  }
}

if($ctr_file_1 == 0)
{
  echo"<b><br>Attached File(s): <br></b>";
  for ($b = 0; $b <= 30; $b++) {
  $xpath_AttachedCountMany = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument[$b].FileName");
  $xpath_AttachedCountb64 = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument[$b].ImageData");
  $xpath_SavedUtc = jsonPath($json_xpathdoc, "$.Data.UniversalEvent.Event.AttachedDocumentCollection.AttachedDocument[$b].SaveDateUTC");
  $MultipleAttach_ctr = $parser->encode($xpath_AttachedCountMany);
  $MultipleAttach_ctrb64 = $parser->encode($xpath_AttachedCountb64);
  $Saved_date = $parser->encode($xpath_SavedUtc);
  $ctr_2 = getArrayName($MultipleAttach_ctr);
  $ctr_2b64 = getArrayName($MultipleAttach_ctrb64);
  $get_valSavedDate = getArrayName($Saved_date);
	 if($ctr_2 === 'false'){  
	 break;	  
  }   
  else{	  
	 $ctr_file_2++;

	 echo "<a target='_blank' href='http://a2bfreighthub.com/xpath_files/$ctr_2'>".$ctr_2."</a>";
	 Base64_Decoder($ctr_2b64,$ctr_2);
	 echo "<br>";
	 //echo"Saved Date:$get_valSavedDate<br>";
  }
 }
}
echo "</div>";
}


?>
</body>
</html>
