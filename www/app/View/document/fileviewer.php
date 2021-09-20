<?php

$file = json_decode(file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/api/get/document/did/'.$this->document_id.'/name,shipment_num,type'));

$file_name = $file[0]->name;
$shipment_num = $file[0]->shipment_num;
$file_type = $file[0]->type;
$email = $this->email;

if(!empty($this->subacc_info)) {
    $email = $this->subacc_info[0]->client_email;
}

// URL: https://cargomation.com/filemanager/cto@mail.com/CW_FILE/S00001055/MSC/Coversheet%20-%20S00001055.pdf
$file = "E:/A2BFREIGHT_MANAGER/".$email."/CW_FILE/".$shipment_num."/".$file_type."/" . $file_name;

// Checks if file is pdf
$ext = pathinfo($file, PATHINFO_EXTENSION);
if($ext != "pdf") {
    echo 'File is not in a "PDF" format.';
    die();
}

try {
    $fp = fopen($file, "r") ;

    header("Cache-Control: maxage=1");
    header("Pragma: public");
    header("Content-type: application/pdf");
    header("Content-Disposition: inline; filename=".$file_name."");
    header("Content-Description: PHP Generated Data");
    header("Content-Transfer-Encoding: binary");
    header('Content-Length:' . filesize($file));
    ob_clean();
    flush();
    while (!feof($fp)) {
       $buff = fread($fp, 1024);
       print $buff;
    }
} catch (\Throwable $th) {
    //throw $th;
    // echo "Preview only available in live.";
    echo "No preview available.";
}

exit;