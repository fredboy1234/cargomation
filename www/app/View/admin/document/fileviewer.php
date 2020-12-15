<?php

$file = json_decode(file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/api/get/document/did/'.$this->document_id.'/name,shipment_num,type'));
$path = "http://".$_SERVER['SERVER_NAME']."/filemanager/" . $this->email . "/CW_FILE/".$file[0]->shipment_num."/".$file[0]->type."/";

header('Content-type: application/pdf;"');
header('Content-Disposition: inline; filename="' . $file[0]->name . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($path . $file[0]->name));
header('Accept-Ranges: bytes');
@readfile($path. $file[0]->name);

?>