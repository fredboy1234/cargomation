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

    public function sendRequestMail2($data) {
        $mail = Utility\Mailer::getInstance();

        $recipient = explode(',', $data['recipient']);
        $message = $data['message'];
        $subject = $data['subject'];

        // $link = "https://cargomation.com/request?token=" . $data['token'];
        // $link = "https://cargomation.com/doctracker?request=true&shipment_num=" . $data['shipment_num'] . "&type=" . $data['document_type'];
        $link = "https://cargomation.com/doctracker/request/" . $data['shipment_num'] . "/" . $data['document_type'] . "/" . $data['token'];
        $message .= "<br><br>";
        $message .= "Thank you.";
        $message .= "<br><br>";
        $message .= "Please click the link provided below to upload requested document.<br>";
        $message .= "Link : <a href=" . $link . ">" . $link . "</a>";

        $results = $mail->sendMail($recipient, $message, $subject);

        return $results['success'];
    }

    public static function sendRequestMail($data) {

      // $recipient = explode(',', $data['recipient']);
      $recipient = $data['recipient'];
      $message = $data['message'];
      $subject = $data['subject'];

      if($data['request_type'] == 'new') {
        $request = 'missing';

        $link = "https://cargomation.com/doctracker/request/" . $data['shipment_num'] . "/" . $data['document_type'] . "/" . $data['token'];
        $message .= "<br><br>";
        $message .= "Thank you.";
        $message .= "<br><br>";
        $message .= "Please click the link provided below to upload requested document.<br>";
        $message .= "Link : <a href=" . $link . ">" . $link . "</a>";
      } else {
        $request = 'edit';

        $link = "https://cargomation.com/doctracker/request/" . $data['shipment_num'] . "/" . $data['document_type'] . "/" . $data['token'];
        $message .= "<br><br>";
        $message .= "Thank you.";
        $message .= "<br><br>";
        $message .= "Please click the link provided below to upload updated document.<br>";
        $message .= "Link : <a href=" . $link . ">" . $link . "</a>";        
      }

      $post_data = array(
        'request' => $request,
        'email' => $recipient,
        'subject' => $subject,
        'message' => $message
      );

      // if the checkbox is checked, use the email provided
      if(isset($data['use_email'])) {
        $post_data['use_email'] = $data['use_email'];
        // if user email was not change
        if(!empty($data['sender'])) {
          $post_data['sender'] = $data['sender'];
        } 
      } 

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cargomation.com/xml_map/mailer/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => http_build_query($post_data),
        CURLOPT_HTTPHEADER => array(
          'Authorization: Basic YTJiYWRtaW46XWkldipLOntwTDhDeyh3',
          'Content-Type: application/x-www-form-urlencoded'
        ),
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);
      $response = json_decode($response);

      try {
        if($response->status == "200"){
            $result['success'] = true;
            $result['message'] = "Message has been sent successfully";
        }else{
            $result['success'] = false;
            $result['message'] = "Failed. Mailer error: " . $response->message;
        }
      } catch (Exception $e) {
        $result['success'] = false;
        $result['message'] = "Failed. Mailer error: " . $response->message;
      }

      return $result['success'];

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
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => false,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => 'request=contact&name=' . $data['name'] . '&email=' . $data['email'] . '&message=' . $data['message'],
          CURLOPT_HTTPHEADER => array(
            'Authorization: Basic YTJiYWRtaW46XWkldipLOntwTDhDeyh3',
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);
    }

}