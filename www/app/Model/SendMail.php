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

    public static function sendContactusAPI($data) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cargomation.com/xml_map/mailer/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'name=' . $data['name'] . '&email=' . $data['email'] . '&message=' . $data['message'],
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic YTJiYWRtaW46XWkldipLOntwTDhDeyh3',
            'Content-Type: application/x-www-form-urlencoded'
        ),
        CURLOPT_SSL_VERIFYPEER, false,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response);
    }

}