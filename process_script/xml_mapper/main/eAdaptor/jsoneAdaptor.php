<?php
require_once( 'json.php' );
require_once( 'jsonpath-0.8.1.php' );


if (isset($_GET['request']) == "document") {
	$shipment_id = $_GET['shipment_id'];
    $request_type = "DocumentRequest";
} else if(isset($_GET['shipment_id'])) {
	$shipment_id = $_GET['shipment_id'];
    $request_type = "ShipmentRequest";
} else {
    echo "Error! Contact Engr. Neil Desucatan"; die();
}



$post_field = '<Universal' . $request_type . ' xmlns="http://www.cargowise.com/Schemas/Universal/2011/11" version="1.1">
<' . $request_type . '>
    <DataContext>
        <DataTargetCollection>
            <DataTarget>
                <Type>ForwardingShipment</Type>
                <Key>' . $shipment_id . '</Key>
            </DataTarget>
        </DataTargetCollection>
        <Company>
            <Code>SYD</Code>
        </Company>
        <EnterpriseID>A2B</EnterpriseID>
        <ServerID>TRN</ServerID>
    </DataContext>

</' . $request_type . '>
</Universal' . $request_type . '>';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$curl = curl_init();
header( 'Content-Type: text/plain' );
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://a2btrnservices.wisegrid.net/eAdaptor",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $post_field,
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic QTJCOkh3N20zWGhT",
    "Content-Type: text/xml",
    "Cookie: WEBSVC=72888934a8b8baad"
  ),
));
$parser = new Services_JSON( SERVICES_JSON_LOOSE_TYPE );
curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
$response = curl_exec($curl);

curl_close($curl);
$xml_universal             = simplexml_load_string( $response );
echo $xml_universal = json_encode( $xml_universal, JSON_PRETTY_PRINT );
?>
