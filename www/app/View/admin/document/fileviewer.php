<?php

$file = json_decode(file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/api/get/document/did/'.$this->document_id.'/name,shipment_num,type'));

$file_name = $file[0]->name;
$shipment_num = $file[0]->shipment_num;
$file_type = $file[0]->type;

$url = "https://cargomation.com/filemanager/".$this->email."/CW_FILE/".$shipment_num."/".$file_type."/" . rawurlencode($file_name);

$cookie = 'cookies.txt';
$timeout = 30;

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout );
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
// curl_setopt($CurlConnect, CURLOPT_POSTFIELDS, $request);
// curl_setopt($CurlConnect, CURLOPT_USERPWD, $login.':'.$password);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Encoding: none','Content-Type: application/pdf')); 

$result = curl_exec($ch);
header('Cache-Control: public'); 
header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="'.$file[0]->name.'"');
header('Content-Length: '.strlen($result));
curl_close($ch);
echo $result;

?>