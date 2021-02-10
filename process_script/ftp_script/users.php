<?php
require_once('connection.php');
$sqluser_info = "SELECT * FROM dbo.user_role INNER JOIN dbo.users ON dbo.user_role.user_id = dbo.users.id
				 WHERE
				 dbo.user_role.role_id = 1";
$execRecord_userinfo = sqlsrv_query( $conn, $sqluser_info );
$return_user    = sqlsrv_has_rows( $execRecord_userinfo );
if($return_user == true){
	while( $row_user = sqlsrv_fetch_array( $execRecord_userinfo, SQLSRV_FETCH_ASSOC) ) 
	{
	$user_email = $row_user['email'];
	$fpath = array('CW_XML/', 'CW_USERS/', 'CW_SUCCESS/', 'CW_LOG/','CW_FILE/','CW_ERROR/');
	$path = "E:/A2BFREIGHT_MANAGER/$user_email/";

    if ( !is_dir( $path ) )
    {
		foreach ($fpath as $inspath) 
		{
		$insidepath = $path.$inspath;	
        mkdir( $insidepath, 0777, true );
	 }
   }
  }
}
?>