<?php
$serverName = "a2bserver.database.windows.net"; 
$connectionInfo = array( "Database"=>"a2bfreighthub_db", "UID"=>"A2B_Admin", "PWD"=>"v9jn9cQ9dF7W");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     //echo "Connection established.<br />";
}else{
     echo "Connection could not be established";
     die( print_r( sqlsrv_errors(), true));
}

?>