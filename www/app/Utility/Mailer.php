<?php

namespace App\Utility;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Mailer:
 *
 * @author John Alex
 * @since 1.10.1
 */
class Mailer {

    /** @var Mailer */
    private static $_Mailer = null;

    private $host;
    private $port;
    private $username;
    private $password;

    private function __construct() {
        $this->host = Config::get("MAILER_HOST");
        $this->dbname = Config::get("MAILER_NAME");
        $this->username = Config::get("MAILER_USERNAME");
        $this->password = Config::get("MAILER_PASSWORD");
    }

    /**
     * Get Instance:
     * @access public
     * @return Mailer
     * @since 1.0.1
     */
    public static function getInstance() {
        if (!isset(self::$_Mailer)) {
            self::$_Mailer = new Mailer();
        }
        return(self::$_Mailer);
    }

    private function authenticate($object) {
        try {
            $object->Host = $this->host;
            $object->Port = $this->port;
            $object->Username = $this->username;
            $object->Password = $this->password;
        } catch (Exception $e) {
            return $e . " <br> " . $object->ErrorInfo;
        }
    }

    private function useProtocol($object, $type = "", $auth = true, $debug = 0) {
        if(!empty($type)) {
            switch ($type) {
                case 'smtp':
                    try {
                        $object->isSMTP();
                        $object->Mailer = "smtp";
                        // $mail->SMTPDebug  = 0;  
                        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                
                        $object->SMTPAuth   = TRUE;
                        $object->SMTPSecure = "tls";
                    } catch (Exception $e) {
                        return $e . " <br> " . $object->ErrorInfo;
                    }
                    break;

                case 'sendmail':
                    // For localhost, standard Sendmail program
                    // Set mailer with this method 
                    $object->isSendmail();
                break;

                case 'qmail':
                    // If you prefer Unix, then use qmail:
                    // Set mailer with this method 
                    $object->isQmail();
                break;
                
                default:
                    # code...
                    break;
            }
        }

    }

    public function sendMail($recipients = [], $message = "", $subject = "", $param = []) {
        $mail = new PHPMailer();
        
        // Check authentication 
        self::authenticate($mail);

        // Use SMTP as Protocol
        self::useProtocol($mail, 'smtp');

        $mail->isHTML(true);
        if(is_array($recipients)) {
            foreach ($recipients as $recipient) {
                //$mail->addAddress($recipient['email'], $recipient['name']);
				$mail->addAddress($recipient);
            }
        } else {
            // $mail->addAddress($recipients);
			$mail->addAddress($recipients['email']);
        }
        $mail->SMTPKeepAlive = true; // add it to keep SMTP connection open after each email sent
        $mail->setFrom("noreply@cargomation.com", "no-reply");
        // $mail->addReplyTo("noreply@cargomation.com", "reply-to-name");
        //$mail->addCC("backup@a2bfreighthub.com", "A2B");
        $mail->Subject = $subject;
        // $mail->Body = $message;
        // $mail->AltBody = 'This is a plain-text message body';
        // $content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";
        $content = $message;
        // $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        // $mail->addAttachment('images/phpmailer_mini.png');
        $mail->msgHTML($content); 
        try {
            if($mail->send()){
                // echo "Message has been sent successfully";
                $result['success'] = true;
                $result['message'] = "Message has been sent successfully";
            }else{
                // echo 'Message could not be sent.';
                // echo 'Mailer Error: ' . $mail->ErrorInfo;
                $result['success'] = false;
                $result['message'] = "Failed. Mailer error: {$mail->ErrorInfo}";
            }
        } catch (Exception $e) {
            // echo "Mailer Error: " . $mail->ErrorInfo;
            $result['success'] = false;
            $result['message'] = "Failed. Mailer error: {$mail->ErrorInfo}";
        }


        return $result;
    }

    
    public function sendMailGroup($recipients = [], $message = [], $subject = "", $param = []) {
        $mail = new PHPMailer;
        $body = file_get_contents('contents.html');

        // Check authentication 
        self::authenticate($mail);

        // Use SMTP as Protocol
        self::useProtocol($mail, 'smtp');

        $mail->SMTPKeepAlive = true; // add it to keep SMTP connection open after each email sent

        $mail->setFrom('list@example.com', 'List manager');
        $mail->Subject = "New Mailtrap mailing list";

        // $recipients = [
        //     ['email' => 'max@example.com', 'name' => 'Max'],
        //     ['email' => 'box@example.com', 'name' => 'Bob']
        // ];

        foreach ($recipients as $recipient) {
            $mail->addAddress($recipient['email'], $recipient['name']);

            $mail->Body = "<h2>Hello, {$recipient['name']}!</h2> <p>How are you?</p>";
            $mail->AltBody = "Hello, {$recipient['name']}! \n How are you?";

            try {
                $mail->send();
                echo "Message sent to: ({$recipient['email']}) {$mail->ErrorInfo}\n";
            } catch (Exception $e) {
                echo "Mailer Error ({$recipient['email']}) {$mail->ErrorInfo}\n";
            }

            $mail->clearAddresses();
        }

        $mail->smtpClose();
    }

}