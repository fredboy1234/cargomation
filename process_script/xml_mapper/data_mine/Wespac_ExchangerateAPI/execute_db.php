<?php
require_once ('../../main/connection.php');
require_once ('../../main/jsonpath-0.8.1.php');
require_once ('../../main/json.php');

$test;
$json = file_get_contents('https://www.cargomation.com/exchangerate/');
$data = json_decode($json,true);
$json_url = "$.data.Brands.WBC.Portfolios['Foreign Exchange Currencies'].Products";
$curr_table = "dbo.currency";
$curr_rate = "dbo.currency_rate";
$parser = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
$arr = array();

$curr_path = array('RATECODE','PRODUCT','UNIT','TTBuy','TTBuyFee','TTSell','TTSellFee','EffectiveDate','EffectiveTime');

$sql = "SELECT * from {$curr_table};";
$execute = sqlsrv_query($conn, $sql);
while ($data_row = sqlsrv_fetch_array($execute, SQLSRV_FETCH_ASSOC)) 
{
	foreach($curr_path as $ctr => $data_arr){
	  $code = $data_row['currency_code'];
	  $code_desc = $data_row['currency_desc'];
	  $path = $json_url."['$code_desc'].Rates.$code.$data_arr";
	  $currency =jsonPath($data,$path);
      $curr = $parser->encode($currency);
	  $curr = getArrayName($curr);
	  $data_item[] = $curr;
	}
	 $arr_date = explode("/",$data_item[7]);
	 $date =  $arr_date[2]."-".$arr_date[1]."-".$arr_date[0];
	 $sql_data = "SELECT * from {$curr_rate} WHERE ";
	 $sql_data .="RATECODE = '".$data_item[0]."' AND ";
	 $sql_data .="EffectiveDate ='".$date."'";
	 $exec = sqlsrv_query($conn, $sql_data);
	 $return_rate = sqlsrv_has_rows($exec);
	 if ($return_rate === false) {
         if(strval($data_item[3])==""){
			 $val_3 = 0;
		 }
		 elseif(strval($data_item[3])!= ""){
			 $val_3 = $data_item[3];
		 }
		 
		  if(strval($data_item[4])==""){
			 $val_4 = 0;
		 }
		 elseif(strval($data_item[4])!=""){
			 $val_4 = $data_item[4];
		 }
		 
		 if(strval($data_item[5])==""){
			 $val_5 = 0;
		 }
		 elseif(strval($data_item[5])!=""){
			 $val_5 = $data_item[5];
		 }
 
		 if(strval($data_item[6])==""){
			 $val_6 = 0;
		 }
		 elseif(strval($data_item[6])!=""){
			 $val_6 = $data_item[6];
		 }
		 $PRD = str_replace("'", "", $data_item[1]); 
		 $sql_insert="INSERT INTO {$curr_rate}";
		 $sql_insert .="(RATECODE,PRODUCT,UNIT,TTBuy,TTBuyFee,TTSell,TTSellFee,EffectiveDate,EffectiveTime) VALUES";
		 $sql_insert .="('".$data_item[0]."','".$PRD."','".$data_item[2]."',".$val_3.",".$val_4.",".$val_5.",".$val_6.",'".$date."','".$data_item[8]."')";
		 $exec_insert = sqlsrv_query($conn, $sql_insert);
	 }
	 $data_item = null;
}
 
function getArrayName($val){ 
		return str_replace(array('["','"]','\"','"','[',']','\/'),array("","","","","","","/"),$val);
}

?>
