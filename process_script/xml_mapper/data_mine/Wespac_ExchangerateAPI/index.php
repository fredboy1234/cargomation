<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
 /*live exchange rate from westpac*/
 $url = "https://www.westpac.com.au/bin/getJsonRates.wbc.fxc.json";
 echo $json = file_get_contents($url);

  
  
?>
