<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once ('json.php');
require_once ('jsonpath-0.8.1.php');

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://www.westpac.com.au/bin/getJsonRates.wbc.fxc.json',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => false,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));
$parser = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($curl);
$json_xpathdoc = json_decode($response, true);

$curr = array("Brunei Dollar", "Canadian Dollar", "CFP Franc", "Chinese Yuan","Danish Krone","Euro","Fiji Dollar","Hong Kong Dollar","Indian Rupee","Indonesian Rupiah","Japanese Yen","Malaysian Ringgit","New Zealand Dollar","Norwegian Krone","Pakistani Rupee","Papua New Guinean Kina","Philippine Peso","Pound Sterling","Samoan Tala","Saudi Riyal","Singapore Dollar","Solomon Islands Dollar","South African Rand","Swedish Krona","Swiss Franc","Thai Baht","Tongan Pa'anga","United Arab Emirates Dirham","United States Dollar","Vanuatu Vatu");
echo '{
	"apiVersion": "1.0",
	"status": 1,
	"data": {
		"Brands": {
			"WBC": {
				"Brand": "WBC",
				"Portfolios": {
					"Foreign Exchange Currencies": {
						"PortfolioId": "FXC","Products": {';
foreach ($curr as $key => $value) {
if ($key === array_key_last($curr)) {
$ccurr = jsonPath($json_xpathdoc, "$.data.Brands.WBC.Portfolios.Foreign Exchange Currencies.Products.".$value."");
$ccurr = $parser->encode($ccurr);
$var = '"'.$value.'"'.": {".substr($ccurr, 2);
echo rtrim($var, "]")."}}}}}}}";     
 }
 else{
$ccurr = jsonPath($json_xpathdoc, "$.data.Brands.WBC.Portfolios.Foreign Exchange Currencies.Products.".$value."");
$ccurr = $parser->encode($ccurr);
$var = '"'.$value.'"'.": {".substr($ccurr, 2);
echo rtrim($var, "]").",";
 }

}
  
?>
