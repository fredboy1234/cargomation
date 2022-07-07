<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
$curl = curl_init();
if(isset($_GET['val'])){
	$value = str_replace(' ', '', strval($_GET['val']));
	curl_setopt_array($curl, array(
		  CURLOPT_URL =>'https://maps.googleapis.com/maps/api/geocode/json?address={'.$value.'}&key=AIzaSyD18geTt29i8C2oPSu-rd5pFg-VqhHefwA',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => false,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}
}
else{
 $array = array("Res" => "Invalid Request");
 echo json_encode($array);
}