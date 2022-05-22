<?php

namespace App\Model;

use Exception;
use App\Utility;

/**
 * User Register Model:
 *
 * @author John Alex
 * @since 1.0.2
 */
class UserRegister {

    /** @var array The register form inputs. */
    private static $_inputs = [
        "first-name" => [
            "required" => true
        ],
        "last-name" => [
            "required" => true
        ],
        "email" => [
            "filter" => "email",
            "required" => true,
            "unique" => "users"
        ],
        "password" => [
            "min_characters" => 6,
            "required" => true
        ],
        "password_repeat" => [
            "matches" => "password",
            "required" => true
        ],
    ];

    /**
     * Register: Validates the register form inputs, creates a new user in the
     * database and writes all necessary data into the session if the
     * registration was successful. Returns the new user's ID if everything is
     * okay, otherwise turns false.
     * @access public
     * @return boolean
     * @since 1.0.2
     */
    public static function register($accId, $contactID = "") {
        
        // Validate the register form inputs.
        // if (!Utility\Input::check($_POST, self::$_inputs)) {
        //     return false;
        // }
        try {

            // Generate a salt, which will be applied to the during the password
            // hashing process.
            $salt = Utility\Hash::generateSalt(32);

            // Insert the new user record into the database, storing the unique
            // ID which will be returned on success.
            $User = new User;
            $userID = $User->createUser([
                "email" => Utility\Input::post("email"),
                "first_name" => Utility\Input::post("first-name"),
                "last_name" => Utility\Input::post("last-name"),
                "password" => Utility\Hash::generate(Utility\Input::post("password"), $salt),
                "salt" => $salt    
            ]);

            //insert web service
            // $User->insertWebService([
            //     "user_id" => $userID,
            //     "webservice_link" => Utility\Input::post("webservice-link"),
            //     "webservice_username" => Utility\Input::post("webservice-username"),
            //     "webservice_password" => Utility\Input::post("webservice-password"),
            //     "server_id" => Utility\Input::post("server-id"),
            //     "enterprise_id" => Utility\Input::post("enterprise-id"),
            //     "isactive" => "Y",
            //     "company_code" => "SYD"
            // ]);

            // checks for the not required inputs
            // $organization_code = Utility\Input::post("organization_code");
            // $organization_code = (isset($organization_code)) ? $organization_code : "";
            // $company_name = Utility\Input::post("company_name");
            // $company_name = (isset($company_name)) ? $company_name : "";

            if(!empty($contactID)) {
                $User->updateUserContactInfo([
                    "user_id" => $userID,
                    "email_address" => Utility\Input::post("email"),
                    "organization_code" => Utility\Input::post("organization_code"),
                    "company_name" => Utility\Input::post("company_name"),
                    "registered_date" => date('Y-m-d H:i:s'), // Current datetime
                    "status" => 1
                ], $contactID);

            } else {
                $User->putUserContactInfo([
                    "admin_id" => $accId,
                    "user_id" => $userID,
                    "email_address" => Utility\Input::post("email"),
                    "organization_code" => Utility\Input::post("organization_code"),
                    "company_name" => Utility\Input::post("company_name"),
                    "is_default" => 'N',
                    "registered_date" => date('Y-m-d H:i:s'), // Current datetime
                    "status" => 1
                ]);
                $contactID = NULL;
            }

            //insert user info
            $User->insertUserInfo([
                "user_id" => $userID,
                "first_name" => Utility\Input::post("first-name"),
                "last_name" => Utility\Input::post("last-name"),
                "email" => Utility\Input::post("email"),
                "phone" => Utility\Input::post("phone"),
                "address"=> Utility\Input::post("address"),
                "city" => Utility\Input::post("city"),
                "postcode" => Utility\Input::post("zip"),
                "country_id" => "",
                "account_id" => $accId,
                //"subscription_id" => Utility\Input::post("subcription"),
                "subscription_id" => 1, // Basic Account Plan
                "status" => 0, // Not Verified yet
                "registered_date" => date('Y-m-d H:i:s'), // Current datetime
                "organization_code" => Utility\Input::post("organization_code"),
                "contact_id" => $contactID,
            ]);

            //insert user role
            $User->insertUserRole([
                "user_id" => $userID,
                "role_id" => Utility\Input::post("role")
            ]);
            
            //create ftp for user
            self::createFTP(Utility\Input::post("email"));

            // Write all necessary data into the session as the user has been
            // successfully registered and return the user's unique ID.
            Utility\Flash::success(Utility\Text::get("REGISTER_USER_CREATED"));
            echo json_encode($userID);
        } catch (Exception $ex) {
            Utility\Flash::danger($ex->getMessage());
            echo json_encode(["results"=>'hala na hulog log log']);
        }
        echo json_encode(['results'=>false]);
    }

    /**
     * Request.
     * @access public
     * @since 1.0.2
     */
    public static function createFTP($email){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cargomation.com/ftp_msc/',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => false,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => 'ftuser='.$email,
          CURLOPT_HTTPHEADER => array(
            'Authorization: Basic YTJiaHViYWRtaW46XWkldipLOntwTDhDeyh3',
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;
    }

}
