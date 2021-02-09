<?php
	include ( '../../../PdfToText.phpclass' ) ;

	function  output ( $message )
	   {
		if  ( php_sapi_name ( )  ==  'cli' )
			echo ( $message ) ;
		else
			echo ( nl2br ( $message ) ) ;
	    }

//$pdf = new PdfToText ( 'SAMPLE FILE 006.pdf' ) ;
//output($pdf -> Text);
$ct = 1;
$ctr = 0;
echo "<body style='font-family:courier'>";
foreach ( glob( "C:/inetpub/wwwroot/process_script/parse/AI/TEST_AI/example_ai/shipment/*.pdf" ) as $filename )
{
$ctr++;
}
$ct = $ctr;
//$AI_CODE = array("COMMERCIAL INVOICE", "LADING NUMBER", "SEA WAYBILL", "ARRIVAL NOTICE","PACKING LIST","TRANSPORT BILL OF LADING");
echo "<h2>CW1 AI DOCUMENT IDENTIFIER<br /></h2>";
echo "Files Found <b style='color:red'>$ct</b>:<br />";
foreach ( glob( "C:/inetpub/wwwroot/process_script/parse/AI/TEST_AI/example_ai/shipment/*.pdf" ) as $filename )
{
	$getName = explode("/",$filename);
	// var_dump($getName[9]);
	$pdf = new PdfToText ("C:/inetpub/wwwroot/process_script/parse/AI/TEST_AI/example_ai/shipment/".$getName[9]."" );
    $AI_CODE = array("COMMERCIAL INVOICE", "LADING NUMBER", "SEA WAYBILL", "ARRIVAL NOTICE","PACKING LIST","TRANSPORT BILL OF LADING");

    foreach ($AI_CODE as &$value) {
    $pos = strpos(strtoupper($pdf -> Text), $value);
    
    if(strval($pos) != ""){

    if(strval(strpos(strtoupper($pdf -> Text),"LADING NUMBER")) != "" || strval(strpos(strtoupper($pdf -> Text),"TRANSPORT BILL")) != ""){	
    		$getType = "BOL - BILL OF LADING";
    	}
    	elseif(strval(strpos(strtoupper($pdf -> Text),"COMMERCIAL INVOICE")) != ""){
    		$getType = "CIV - COMMERCIAL INVOICE";
    	}
    	elseif(strval(strpos(strtoupper($pdf -> Text),"PACKING LIST")) != "")
    	{
    		$getType = "PKL - PACKING LIST";
    	}
    	elseif(strval(strpos(strtoupper($pdf -> Text),"ARRIVAL NOTICE")) != ""){
    		$getType = "ARN - ARRIVAL NOTICE";
    	}
    	elseif(strval(strpos(strtoupper($pdf -> Text),"SEA WAY")) != ""){
    		$getType = "SWB - SEA WAYBILL";
    	}
    	else{
    		$getType = "Unknown Document";

		}
    echo "<b>File Name:</b> <a target='_blank' href='http://a2bfreighthub.com/TEST_API/$getName[9]'>".$getName[9]."</a> -> Detected keyword: ".$value." -> Document Type: ".$getType." <button><a target='_blank' href='http://a2bfreighthub.com/TEST_API/view.php?file=$getName[9]'>VIEW PARSED DATA</a></button>";
	echo "<br />";
		
    } 
}}
echo "</body>";

?>



       
