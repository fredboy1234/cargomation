<?php
  foreach (glob("E:/A2BFREIGHT_MANAGER/CLIENT_00001/CW_XML/*") as $filename) {
	 $myxmlfilecontent = file_get_contents($filename);
     //$xmlpath = "http://a2bfreighthub.com/Vdirect_cwxml_USER001/".basename($filename);

$xml = simplexml_load_string($myxmlfilecontent);
echo $json = json_encode($xml);
$array = json_decode($json,TRUE);
	
}
  
  
  
?>