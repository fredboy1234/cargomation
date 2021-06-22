<?php
/*AUTHOR :Freidrich Valenzuela*/

// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once ('connection.php');

$valid_passwords = array(
    "a2bhubadmin" => "]i%v*K:{pL8C{(w"
);
$valid_users = array_keys($valid_passwords);

if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
{
    $user = $_SERVER['PHP_AUTH_USER'];
    $pass = $_SERVER['PHP_AUTH_PW'];
    $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
}
else
{
    $validated = "";
}

if (!$validated)
{
    header('WWW-Authenticate: Basic realm="a2bfreighthub Authenticate"');
    header('HTTP/1.0 401 Unauthorized');
    die("Not authorized to access this resources.");
}
else
{
    //user info
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    if ($requestMethod == "POST")
    {
        if (isset($_REQUEST['ftuser']))
        {
            $username = $_POST['ftuser'];
            $password = random_password(8);
            $date_added = date("Ymd");		
			$seed = str_split("!\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~");
			shuffle($seed);
			$rand = '';

			foreach (array_rand($seed, 64) as $k) {
					$rand .= $seed[$k];
			}

			$passwd = $password;		
			$salt_raw = utf8_encode($rand);
			$salt_html = htmlentities($salt_raw);
			$passwd = utf8_encode($passwd);
			$salted_password = $passwd . $salt_raw;
			$encoded_password = strtoupper(hash('SHA512', $salted_password)); 

			// $salt_html goes into <Option Name="Salt">
			// $encoded_password goes into <Option Name="Pass">
            $userDir = "E/:A2BFREIGHT_MANAGER/" . $username . "/CW_XML/";
            $checkuser = '<User Name="' . $username;
        }
        else
        {
            $username = "emptyuser";
            $checkuser = '<User Name="' . $username;
        }

        if (trim($username) == "")
        {
            $array = array(
                "Res" => "Null"
            );
            echo json_encode($array);
            die();
        }

        if (strlen(trim($username)) <= 5)
        {
            $array = array(
                "Res" => "User must be at least {6} characters long."
            );
            echo json_encode($array);
            die();
        }

        //location of filezilla
        $fileloc = "C:/Program Files (x86)/FileZilla Server/";
        $filelocfile = ($fileloc . "FileZilla Server.xml");
        //echo $filelocfile;
        ////////////////
        // start add filezilla user
        ////////////////
        //Check to see if user name is already used
        $fp = fopen($filelocfile, "r");
        $data = fread($fp, filesize($filelocfile));
        $pos1 = strpos($data, $checkuser); //find user name
        //echo (".".$pos1.".");
        fclose($fp);
        
		//check existing ftp user
		$sql="SELECT * FROM ftp_users WHERE email = '$username'";
		$exec_ftpuser = sqlsrv_query($conn, $sql);
		$user_exist = sqlsrv_has_rows($exec_ftpuser);
	
        //if user not found .. add
        if ($user_exist == "")
        {
            $sqluser_info = "SELECT TOP (1) * FROM dbo.users
			INNER JOIN dbo.user_role
			ON dbo.users.id = dbo.user_role.user_id WHERE
			dbo.users.email = '$username'
			AND dbo.user_role.role_id = 2";
            $execRecord_userinfo = sqlsrv_query($conn, $sqluser_info);
            $return_user = sqlsrv_has_rows($execRecord_userinfo);
            if ($return_user === true)
            {
                while ($row_user = sqlsrv_fetch_array($execRecord_userinfo, SQLSRV_FETCH_ASSOC))
                {
                    $user_id = $row_user['user_id'];
                }
            }
            else
            {

                $scc = array("Res" => "User Email does not exist / Invalid role ID!!");
                http_response_code(401);
                echo json_encode($scc);
                die();
            }

            // user setting for FileZilla FTP
            $fileread = 1; //Files Read  1 = YES  0 = NO
            $filewrite = 1; //Files Write
            $filedelete = 1; //Files Delete
            $fileappend = 1; //Files Append, must have Write on
            $dircreate = 1; //Directory Create
            $dirdelete = 1; //Directory Delete
            $dirlist = 1; //Directory List
            $dirsubdirs = 1; //Directory + Subdirs
            // Aktuelle Config wird eingelesen
            $lines = file($filelocfile);

            // Copy Config for backup
            rename($filelocfile, $fileloc . $username . "-FileZilla Server.xml");

            // open Config for writing
            $file = fopen($filelocfile, "a");

            for ($i = 0;$i < sizeof($lines);$i++)
            {
                fwrite($file, $lines[$i]);

                // write new information on top of list after "<Users>"
                if (strstr($lines[$i], "<Users>"))
                {

                    fwrite($file, '<User Name="' . $username . '">
					<Option Name="Pass">' . $encoded_password . '</Option>
					<Option Name="Salt">'.$salt_html.'</Option>
					<Option Name="Group"/>
					<Option Name="Bypass server userlimit">0</Option>
					<Option Name="User Limit">0</Option>
					<Option Name="IP Limit">0</Option>
					<Option Name="Enabled">1</Option>
					<Option Name="Comments"/>
					<Option Name="ForceSsl">0</Option>
					<IpFilter>
					<Disallowed/>
					<Allowed/>
					</IpFilter>
					<Permissions>
					<Permission Dir="E:\\A2BFREIGHT_MANAGER\\'.$username.'\\CW_XML">
					<Option Name="FileRead">' . $fileread . '</Option>
					<Option Name="FileWrite">' . $filewrite . '</Option>
					<Option Name="FileDelete">' . $filedelete . '</Option>
					<Option Name="FileAppend">' . $fileappend . '</Option>
					<Option Name="DirCreate">' . $dircreate . '</Option>
					<Option Name="DirDelete">' . $dirdelete . '</Option>
					<Option Name="DirList">' . $dirlist . '</Option>
					<Option Name="DirSubdirs">' . $dirsubdirs . '</Option>
					<Option Name="IsHome">1</Option>
					<Option Name="AutoCreate">0</Option>
					</Permission>
					</Permissions>
					<SpeedLimits DlType="0" DlLimit="10" ServerDlLimitBypass="0" UlType="0" UlLimit="10" ServerUlLimitBypass="0">
					<Download/>
					<Upload/>
					</SpeedLimits>
					</User>
					');
                }
            }
			
			
			//store ftp account
			$sql = "INSERT INTO ftp_users (email,ftp_password,date_added,isActive) VALUES ('$username','$password','$date_added','Y');";
			$exec_ftp = sqlsrv_query($conn, $sql);
			 

            // Close xml file
            fclose($file);
            if (!file_exists("C:/inetpub/wwwroot/process_script/xml_mapper/batch_file/$username.bat"))
            {
                $file_loc = "C:/inetpub/wwwroot/process_script/xml_mapper/batch_file/$username.bat";
                $batch = fopen("C:/inetpub/wwwroot/process_script/xml_mapper/batch_file/$username.bat", "w");
                $filebatch = fopen($file_loc, "a");
                fwrite($filebatch, 'powershell.exe -noprofile -command "Invoke-WebRequest -Uri localhost/xml_map/?user_id=' . $user_id . '"');
                fclose($filebatch);
                fclose($batch);
            }

            http_response_code(200);
            $scc = array("Res" => "Successfully Added","Generated pass" => $password);
            echo json_encode($scc);
            
        }
	
        else
        {
            http_response_code(200);
            $scc = array("Res" => "Account already exist.");
            echo json_encode($scc); //did not add user, user name already used
            
        }
    }
    else
    {
        http_response_code(401);
        echo json_encode("Invalid Request.");
    }
}
/*end of Auto FTP*/

function random_password( $length = 8 ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}


?>
