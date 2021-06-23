	<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require("Exception.php");
	require("PHPMailer.php");
	require("SMTP.php");

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	$valid_passwords = array ("a2badmin" => "]i%v*K:{pL8C{(w");
	$valid_users = array_keys($valid_passwords);
	$user="";

	if(isset($_SERVER['PHP_AUTH_USER']) || isset($_SERVER['PHP_AUTH_PW'])){
	$user = $_SERVER['PHP_AUTH_USER'];
	$pass = $_SERVER['PHP_AUTH_PW'];
	}
	$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
	$error = []; 
	if (!$validated) {
	  header('WWW-Authenticate: Basic realm="Cargomation Authenticate"');
	  header('HTTP/1.0 401 Unauthorized');
	  $response = array("data"=>$error,"status"=>401,"message"=>"Not Authorized.");
	  echo json_encode($response);
	  die();
	}
	else
	{
	 if($_SERVER["REQUEST_METHOD"]	== "POST"){
	 	
		$name=$_POST['name'];
		$email=$_POST['email'];
		$messagecontent=$_POST['message'];
		
		if(validate_email($email) == false){
		  	$response_code = http_response_code(200);
			$response = array("data"=>$error,"status"=>200,"message"=>"Invalid Email Address.");
		    echo json_encode($response);
			die();
		}

	    $autoemail = "support@cargomation.com";
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->IsHTML(true);
		$mail->SMTPDebug = false;
		$mail->Mailer = "smtp";
		$mail->Host = "mail.smtp2go.com";
		$mail->Port = "80"; // 8025, 587 and 25 can also be used. Use Port 465 for SSL.
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'tls'; 
		$mail->Username = "info@a2bsolutiongroup.com";
		$mail->Password = "YkblwuN7hBSL";

		$mail->From = "{$email}";
		$mail->FromName = "{$autoemail}";
		$mail->AddAddress("{$autoemail}", "");
		$mail->AddReplyTo("{$email}", "{$name}");
		
		$mail->addCustomHeader('MIME-Version: 1.0');
		$mail->addCustomHeader('Content-Type: text/html; charset=ISO-8859-1'); 

		$subject = "Cargomation | Support Inquiry Info.";
		$msg1 = '
			<p>Name: '.$name.'</p>
			<p>Email: '.$email.'</p>
			<p>Message: '.$messagecontent. '</p><br />'; 

		//SEND BACK EMAIL TO INFO@A2BSOLUTIONGROUP.COM
		$mail->Subject = $subject;
		$mail->Body = $msg1;
		$mail->WordWrap = 50;
		$mail->Send();

		$response_code = http_response_code(200);
		$response = array("status"=>"200","message"=>"Message Sent.");
    	// show products data in json format
    	echo json_encode($response);


	}else{
		$response_code = http_response_code(200);
		$response = array("data"=>$error,"status"=>"200","message"=>"Invalid request");
    	// show products data in json format
    	echo json_encode($response);
	} 		
}

function validate_email($email) {
    return (preg_match("/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/", $email) || !preg_match("/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/", $email)) ? false : true;
}

?>