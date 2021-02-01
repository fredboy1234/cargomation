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
        $subject = $data['title'];

        $results = $mail->sendMail($recipient, $message, $subject);

        return $results['success'];
    }

}