<html>
<head></head>
<body>
<form name="form" action="" method="get">
  Tracking Number: <input type="text" name="trackingnumber" id="subject" value="">
  <input type="submit" name="my_form_submit_button" 
           value="SEARCH"/>
</form>
</body>
</html>
<?php

if(isset($_GET['trackingnumber'])){
$tracking = $_GET['trackingnumber'];
libxml_use_internal_errors(true);
$htmlContent = file_get_contents("https://www.anl.com.au/ebusiness/tracking/search?Reference=$tracking&FromHome=true");
		
	$DOM = new DOMDocument();
	$DOM->loadHTML($htmlContent);
	
	
	$Detail = $DOM->getElementsByTagName('td');
	$aDataTableHeaderHTML=[];
    // #Get header name of the table
	foreach($Detail as $NodeHeader) 
	{
		if(!empty(trim($NodeHeader->textContent))){
		$aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
		}
	}
	//var_dump($aDataTableHeaderHTML); 
	
	$data = array_chunk($aDataTableHeaderHTML, 5);

	print "<pre>";
	print_r($data);
	print "</pre>";
}
?>
