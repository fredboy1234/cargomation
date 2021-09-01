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
	 	if(isset($_POST['name'])){
			$name = $_POST['name'];
		}
		else{$name ="";}
		
		$email=$_POST['email'];
		$messagecontent=$_POST['message'];
		$requesttype=$_POST['request'];
		
		
		// if(validate_email($email) == false){
		  	// $response_code = http_response_code(200);
			// $response = array("data"=>$error,"status"=>200,"message"=>"Invalid Email Address.");
		    // echo json_encode($response);
			// die();
		// }

		// default
		$sender = "no-reply@cargomation.com";

		// if the checkbox is checked, use the email provided
		if($_POST['use_email'] == "on") {
			// if user email was not change
			if(empty($sender)) {
				$sender = $_POST['sender'];
			} 
		} 

	    $autoemail = "support@cargomation.com";
	    $autoemail1 = $sender;
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
		
		
		$ctr=0;
		if($requesttype == 'contact'){
		$subject = "Cargomation | Support Inquiry Info.";
		$msg = '<p>Name: '.$name.'</p><p>Email: '.$email.'</p><p>Message: '.$messagecontent. '</p><br />';
		$to = $autoemail;
		$isbcc = false;}
		
		elseif($requesttype == 'missing'){
		$subject = "Cargomation | Request for Missing Document";
		$msg = $messagecontent;
		$to = $email;
		$recipient = explode(',',$to, -1);
		if ($recipient){ $recipient = str_replace(",",";",$to);$isbcc = true;$ctr = explode(';',$recipient);$email=$ctr[0];} else { $recipient = $to;$isbcc = true;}}	
		
		else{
		$subject = "Cargomation | Request for Document Update";
		$msg = $messagecontent;
		$to = $email;
		$recipient = explode(',',$to, -1);
		if ($recipient){ $recipient = str_replace(",",";",$to);$isbcc = true;$ctr = explode(';',$recipient);$email=$ctr[0];} else { $recipient = $to;$isbcc = true;}}	
		
			
		$mail->addCustomHeader('MIME-Version: 1.0');
		$mail->addCustomHeader('Content-Type: text/html; charset=ISO-8859-1'); 
		$mail->From = "{$email}";
		if($isbcc == true){
		$mail->FromName = "{$autoemail1}";
		}else{
		$mail->FromName = "{$autoemail}";	
		}
		$mail->AddAddress("{$to}", "");
		
		if($isbcc == true){
		if(is_array($ctr)){
		if(count($ctr)>0){
		foreach($ctr as $ctr1=>$value){
		  if($ctr > 0){
                $mail->AddBCC($value); }}}}
		  $mail->AddReplyTo("{$autoemail1}", "A2b Cargomation");
		}
		else{$mail->AddReplyTo("{$email}", "{$name}");}

		$mail->Subject = $subject;
		$mail->Body = $msg;
		$mail->WordWrap = 50;
		
		try{$mail->Send();}
		catch (Exception $e){
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}

		$response_code = http_response_code(200);
		$response = array("status"=>"200","message"=>"Message Sent.");
    	// show data in json format
    	echo json_encode($response);


	}else{
		$response_code = http_response_code(200);
		$response = array("data"=>$error,"status"=>"200","message"=>"Invalid request");
    	// show data in json format
    	echo json_encode($response);
	} 		
}

function validate_email($email) {
    return (preg_match("/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/", $email) || !preg_match("/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/", $email)) ? false : true;
}

?>
