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

        $recipient = $data['recipient'];
        $message = $data['message'];
        $subject = $data['subject'];

        //$link = "http://a2bfreighthub.com/request?token=" . $data['token'];
        $link = "http://a2bfreighthub.com/doctracker?request=true&shipment_num=" . $data['shipment_num'] . "&type=" . $data['document_type'];

        $message .= "<br><br>";
        $message .= "Thank you.";
        $message .= "<br><br>";
        $message .= "Please click the link provided below to upload requested document.<br>";
        $message .= "Link : <a href=" . $link . ">" . $link . "</a>";

        $results = $mail->sendMail($recipient, $message, $subject);

        return $results['success'];
    }

}