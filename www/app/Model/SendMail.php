<?php

namespace App\Model;

use Exception;
use App\Core;
use App\Utility;

/**
 * Role Model:
 *
 * @author John Alex
 * @since 1.0.6
 */
class SendMail extends Core\Model {

    public function sendRequestMail($data) {
        $mail = Utility\Mailer::getInstance();

        $recipient = explode(',', $data['recipient']);
        $message = $data['message'];
        $subject = $data['subject'];

        // $link = "http://a2bfreighthub.com/request?token=" . $data['token'];
        // $link = "http://a2bfreighthub.com/doctracker?request=true&shipment_num=" . $data['shipment_num'] . "&type=" . $data['document_type'];
        $link = "http://a2bfreighthub.com/doctracker/request/" . $data['shipment_num'] . "/" . $data['document_type'] . "/" . $data['token'];
        $message .= "<br><br>";
        $message .= "Thank you.";
        $message .= "<br><br>";
        $message .= "Please click the link provided below to upload requested document.<br>";
        $message .= "Link : <a href=" . $link . ">" . $link . "</a>";

        $results = $mail->sendMail($recipient, $message, $subject);

        return $results['success'];
    }

    public static function sendContactus($data) {
        $mail = Utility\Mailer::getInstance();
        
        $data['recipient'] = 'support@cargomation.com';
        $data['subject'] = 'Client Support';

        $recipient = explode(',', $data['recipient']);
        $message = $data['message'];
        $subject = $data['subject'];

      
        $message .= "<br><br>";
        $message .= "Message From: ".$data['name'];
        $message .= "Email: ".$data['email'];
        $message .= "<br><br>";
        $message .= "<br>";
        $message .= $data['message'];

        $results = $mail->sendMail($recipient, $message, $subject);
        
        return  $results;
    }

}