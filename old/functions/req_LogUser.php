<?php
include("../connection.php");

	$sqlLog="SELECT TOP 7
	dbo.tblLogUser.logIDUser,
	dbo.tblLogin.loginEmail,
	dbo.tblLogUser.logIDDate,
	dbo.tblUser.userFname
	FROM
	dbo.tblUser
	INNER JOIN dbo.tblLogUser
	 ON dbo.tblUser.userID = dbo.tblLogUser.logIDUser
	INNER JOIN dbo.tblLogin
	 ON dbo.tblUser.userID = dbo.tblLogin.loginID
	ORDER BY
	dbo.tblLogUser.logIDDate DESC;";
	$sqltblLogUser = sqlsrv_query( $conn, $sqlLog);
	while( $row = sqlsrv_fetch_array( $sqltblLogUser, SQLSRV_FETCH_ASSOC) ) 
	{
		$date = $row['logIDDate']->format('Y/m/d H:i:s');
		echo
		'<li>
        <div class="bg-danger"><i class="ti-user"></i></div>User : '.strtoupper($row['userFname']).'<span class="text-muted ml-auto">'.time_elapsed_string(strval($date), true).'</span>
		</li>';
		
	}
	//echo time_elapsed_string("'".$date."'");
	//echo time_elapsed_string('@1367367755'); # timestamp input

	
	function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        //'h' => 'hour',
        'i' => 'min',
        's' => 'sec',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>