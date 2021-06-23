<?php

if(gethostname() == "A2B-Cargomation"){$db="a2bcargomation_db";}else{$db="a2bfreighthub_db";}

$serverName = "a2bserver.database.windows.net"; 
$connectionInfo = array( "Database"=>$db, "UID"=>"A2B_Admin", "PWD"=>"v9jn9cQ9dF7W");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     //echo "Connection established.<br />";
}else{
     echo "Connection could not be established";
     die( print_r( sqlsrv_errors(), true));
}
?>