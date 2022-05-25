<?php
require_once('connection.php');
$sqluser_info = "SELECT * FROM dbo.user_role INNER JOIN dbo.users ON dbo.user_role.user_id = dbo.users.id
                 WHERE
                 dbo.user_role.role_id = 2";
$execRecord_userinfo = sqlsrv_query( $conn, $sqluser_info );
$return_user    = sqlsrv_has_rows( $execRecord_userinfo );
if($return_user == true){
    while( $row_user = sqlsrv_fetch_array( $execRecord_userinfo, SQLSRV_FETCH_ASSOC) ) 
    {
    $user_email = $row_user['email'];
    $path = "E:/A2BFREIGHT_MANAGER/$user_email/";

    $fpath = array('CW_XML/', 'CW_XML/CW_AR_INVOICE/IN/','CW_XML/CW_AR_INVOICE/SUCCESS/','CW_XML/CW_AR_INVOICE/ERROR/',
        'CW_XML/CW_ORDERS/IN/','CW_XML/CW_ORDERS/SUCCESS/','CW_XML/CW_ORDERS/ERROR/',
        'CW_XML/CW_CUSTOMS/IN/','CW_XML/CW_CUSTOMS/SUCCESS/','CW_XML/CW_CUSTOMS/ERROR/',
        'CW_XML/CW_ORG/IN/','CW_XML/CW_ORG/SUCCESS/','CW_XML/CW_ORG/ERROR/',
        'CW_APINVOICE/IN/','CW_APINVOICE/MERGE_FOLDER/',
        'CW_USERS/', 'CW_SUCCESS/', 'CW_LOG/','CW_FILE/','CW_ERROR/');


    if (!file_exists($path)) {
    mkdir($path, 0777, true);
    }
     foreach ($fpath as $inspath) 
      {
        $insidepath = $path.$inspath;
        if(is_dir( $path )){
            mkdir( $insidepath, 0777, true );
       }   
    }          
  }
}
?>
