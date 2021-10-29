<?php

namespace App\Model;

use Exception;
use App\Core;
use App\Utility;

/**
 * User Model:
 *
 * @author John Alex
 * @since 1.0.2
 */
class User extends Core\Model {

    /**
     * Create User: Inserts a new user into the database, returning the unique
     * user if successful, otherwise returns false.
     * @access public
     * @param array $fields
     * @return string|boolean
     * @since 1.0.3
     * @throws Exception
     */
    public function createUser(array $fields) {
        if (!$userID = $this->create("users", $fields)) {
            throw new Exception(Utility\Text::get("USER_CREATE_EXCEPTION"));
        }
        return $userID;
    }

    /**
     * Create User Info: Inserts a new user info into the database.
     * @access public
     * @param array $fields
     * @return null
     * @since 1.0.3
     * @throws Exception
     */
    public function insertUserInfo(array $fields) {
        if (!$userID = $this->create("user_info", $fields)) {
            throw new Exception(Utility\Text::get("USER_CREATE_EXCEPTION"));
        }
        return null;
    }

    // /**
    //  * Create User Webservice: Inserts a new user webservice into the database.
    //  * @access public
    //  * @param array $fields
    //  * @return null
    //  * @since 1.0.3
    //  * @throws Exception
    //  */
    public function insertWebService(array $fields) {
        if (!$userID = $this->create("user_webservice", $fields)) {
            throw new Exception(Utility\Text::get("USER_CREATE_EXCEPTION"));
        }
        return null;
    }

    /**
     * Create User Role: Inserts a new user Role into the database.
     * @access public
     * @param array $fields
     * @return null
     * @since 1.0.3
     * @throws Exception
     */
    public function insertUserRole(array $fields) {
        if (!$userID = $this->create("user_role", $fields)) {
            throw new Exception(Utility\Text::get("USER_CREATE_EXCEPTION"));
        }
        return null;
    }

    /**
     * Create User Contact: Inserts a new user contact into the database.
     * @access public
     * @param array $fields
     * @return null
     * @since 1.0.3
     * @throws Exception
     */
    public function insertUserContact(array $fields) {
        if (!$userID = $this->create("user_contact", $fields)) {
            throw new Exception(Utility\Text::get("USER_CREATE_EXCEPTION"));
        }
        return null;
    }

    /**
     * Get Instance: Returns an instance of the User model if the specified user
     * exists in the database. 
     * @access public
     * @param string $user
     * @return User|null
     * @since 1.0.2
     */
    public static function getInstance($user) {
        $User = new User();
        if ($User->findUser($user)->exists()) {
            return $User;
        }
        return null;
    }

    /**
     * Find User: Retrieves and stores a specified user record from the database
     * into a class property. Returns true if the record was found, or false if
     * not.
     * @access public
     * @param string $user
     * @return boolean
     * @since 1.0.3
     */
    public function findUser($user) {
        $field = filter_var($user, FILTER_VALIDATE_EMAIL) ? "email" : (is_numeric($user) ? "id" : "username");
        return($this->find("users", [$field, "=", $user]));
    }

    /**
     * Update User: Updates a specified user record in the database.
     * @access public
     * @param array $fields
     * @param integer $userID [optional]
     * @return void
     * @since 1.0.3
     * @throws Exception
     */
    public function updateUser(array $fields, $userID = null) {
        if (!$this->update("users", $fields, $userID)) {
            throw new Exception(Utility\Text::get("USER_UPDATE_EXCEPTION"));
        }
    }

    /**
     * Update User profile: Updates a specified user record in the database.
     * @access public
     * @param array $fields
     * @param integer $userID [optional]
     * @return void
     * @since 1.0.3
     * @throws Exception
     */
    public function updateUserProfile(array $fields, $userID = null) {
        $this->update("users", $fields, $userID);
    }

    /**
     * Update User Info: Updates a specified user record in the database.
     * @access public
     * @param array $fields
     * @param integer $userID [optional]
     * @return void
     * @since 1.0.3
     * @throws Exception
     */
    public function updateUserInfo(array $fields, $userID = null) {
        $this->update("user_info", $fields, $userID);
    }

    /**
     * Update User Role: Updates a specified user role in the database.
     * @access public
     * @param array $fields
     * @param integer $userID [optional]
     * @return void
     * @since 1.0.3
     * @throws Exception
     */
    public function updateUserRole(array $fields, $userID = null) {
        $this->query("UPDATE user_role 
                        SET role_id = '{$fields['role_id']}' 
                        WHERE user_id = '{$fields['user_id']}'");
    }

    /**
     * Get User Instance: Get instance of a specified user record in the database.
     * @access public
     * @param int $userID
     * @return Db
     * @since 1.0.3
     * @throws Exception
     */
    public static function getUsersInstance($userID, $roleID) {

        switch ($roleID) {
            case 1:
                $query = "SELECT users.id, 
                    user_info.first_name, 
                    user_info.last_name, 
                    user_info.email,
                    user_role.role_id AS 'role',
                    user_info.status AS 'status',
                    user_info.city,
                    user_images.image_src,
                    subscription.name AS 'plan' FROM users
                    LEFT JOIN user_info ON  users.id = user_info.user_id
                    LEFT JOIN user_role ON  users.id = user_role.user_id
                    LEFT JOIN subscription ON  user_info.subscription_id = subscription.id
                    LEFT JOIN user_images ON user_images.user_id = user_info.user_id 
                    WHERE user_role.role_id = '2'";
                break;
            case 2:
                $query = "SELECT users.id, 
                    user_info.first_name, 
                    user_info.last_name, 
                    user_info.email,
                    user_info.status AS 'status',
                    user_info.city,
                    user_images.image_src,
                    subscription.name AS 'plan' FROM users
                    LEFT JOIN user_info ON  users.id = user_info.user_id
                    LEFT JOIN subscription ON  user_info.subscription_id = subscription.id
                    LEFT JOIN user_images ON user_images.user_id = user_info.user_id
                    WHERE user_info.account_id = {$userID}";
                break;
            case 3:
                $query = "SELECT users.id, 
                    user_info.first_name, 
                    user_info.last_name, 
                    user_info.email,
                    user_info.status AS 'status',
                    user_info.city,
                    user_images.image_src,
                    subscription.name AS 'plan' FROM users
                    LEFT JOIN user_info ON  users.id = user_info.user_id
                    LEFT JOIN subscription ON  user_info.subscription_id = subscription.id
                    LEFT JOIN user_images ON user_images.user_id = user_info.user_id
                    WHERE user_info.account_id = {$userID}";
                break;
            case 4:
                $query = "SELECT users.id, 
                    user_info.first_name, 
                    user_info.last_name, 
                    user_info.email,
                    user_info.status AS 'status',
                    user_info.city,
                    user_images.image_src,
                    subscription.name AS 'plan' FROM users
                    LEFT JOIN user_info ON  users.id = user_info.user_id
                    LEFT JOIN subscription ON  user_info.subscription_id = subscription.id
                    LEFT JOIN user_images ON user_images.user_id = user_info.user_id
                    WHERE user_info.account_id = {$userID}";
                break;
            default:
                # code...
                break;
        }
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    /**
     * Get Profile: Get a specified user info in the database.
     * @access public
     * @param int $user
     * @return array|object
     * @since 1.0.3
     * @throws Exception
     */
    public static function getProfile($user) {
        $Db = Utility\Database::getInstance();
        $user_info = $Db->query("SELECT * FROM user_info WHERE user_id = '{$user}'")->results();
        $user_addr = $Db->query("SELECT CONCAT(user_info.address,' ',user_info.city,', ',countries.countryname) AS 'address' 
                                FROM user_info
                                LEFT JOIN countries ON user_info.country_id = countries.idcountry 
                                WHERE user_info.id = '{$user}'")->results();
        $account_info = $Db->query("SELECT users.id,
                                user_info.account_users AS 'user_count',
                                user_info.subscription_id AS 'type',
                                user_info.status AS 'status',
                                subscription.name AS 'plan',
                                subscription.max_users AS 'max_users' FROM users
                                LEFT JOIN user_info ON  users.id = user_info.user_id
                                LEFT JOIN subscription ON  user_info.subscription_id = subscription.id
                                WHERE users.id = '{$user}'")->results();
        $user_count = $Db->query("SELECT count(*) AS count
                                FROM user_info
                                WHERE account_id = '{$user}'")->results();
        $user_image = $Db->query("SELECT *
                                FROM user_images where user_id = '{$user}'
                                ")->results();

        return [
            "user_info" => $user_info,
            "user_addr" => $user_addr,
            "user_count" => $user_count,
            "account_info" => $account_info,
            "user_image" => $user_image
        ];
    }

    public  function addUserSettings($data) {
        $fields = array(
            $user_id = $data['user'],
            $hub =json_encode($data['settings']['hub']),
            $profile =json_encode($data['settings']['profile']),
            $shipment =json_encode($data['settings']['shipment']),
            $document=json_encode($data['settings']['document']),
        );

        $Db = Utility\Database::getInstance();
        $select = $Db->query("SELECT * from user_settings where user_id = '{$user_id}'")->results();
        if(empty($select)){
            return $Db->query("INSERT
                           INTO user_settings (user_id,hub,profile,shipment,document)
                           VALUES('{$user_id}','{$hub}','{$profile}','{$shipment}','{$document}')");
        }else{
            return $Db->query("UPDATE user_settings 
                                SET hub ='{$hub}' ,profile='{$profile}',shipment='{$shipment}',document='{$document}'
                                WHERE user_id = '{$user_id}'");
        }
        
        //return null;
    }

    public function getUserSettings($user){
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT * 
                                FROM user_settings
                                WHERE user_id = '{$user}' ")->results();
    }

    public function deleteUserSettings($id){
        $Db = Utility\Database::getInstance();
        return $Db->query("DELETE
                            FROM user_settings
                            WHERE id = '{$id}' ");
    }

    /**
     * Update User Settings: Same as addUserSettings but slightly modified
     * @access public
     * @param array|string $column
     * @param array $data
     * @param int $user_id
     * @return null
     * @since 1.0.3
     */
    public function updateUserSettings($column = "*", $data = "", $user_id = "") {

        $value = json_encode($data);

        $Db = Utility\Database::getInstance();
        $select = $Db->query("SELECT {$column} from user_settings where user_id = '{$user_id}'")->results();
        if(empty($select)){
            return $Db->query("INSERT
                           INTO user_settings (user_id, {$column})
                           VALUES('{$user_id}','{$column}')");
        }else{
            return $Db->query("UPDATE user_settings 
                                SET {$column} ='{$value}'
                                WHERE user_id = '{$user_id}'");
        }
        
        //return null;
    }

    /**
     * Insert User Images: Insert User Images etc..
     * @access public
     * @param array $fields
     * @return null
     * @since 1.0.3
     * @throws Exception
     */
    public function inserUserImages(array $fields) {
        $img_src = base64_encode($fields['image_src']);
        //$img_src = $fields['image_src'];
        $Db = Utility\Database::getInstance();
        $Db->query("UPDATE user_images
                set image_type = '' WHERE user_id = '{$fields['user_id']}'
        ");
        return $Db->query("INSERT
                            INTO user_images 
                        (user_id,image_type,uploaded,image_src) 
                        values('{$fields['user_id']}','{$fields['image_type']}',GETDATE(),'{$img_src}')
        ");
    }

    public static function getUsersDashPhoto($userID) {
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT user_images.user_id,user_images.image_src
                            FROM users
                            LEFT JOIN user_info ON  users.id = user_info.user_id
                            LEFT JOIN user_images ON user_images.user_id = users.id
                            WHERE user_info.account_id = {$userID}")->results();
    }

    public static function getUserTheme() {
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT *
                            FROM themes ORDER BY theme_name")->results();
    }

    public  function addUserTheme($data) {
        $fields = array(
            $user_id = $data['user'],
            $theme = $data['theme']
        );

        $Db = Utility\Database::getInstance();
        $select = $Db->query("SELECT * from user_settings where user_id = '{$user_id}'")->results();
        if(empty($select)){
            return $Db->query("INSERT
                           INTO user_settings (user_id,theme)
                           VALUES('{$user_id}','{$theme}')");
        }else{
            return $Db->query("UPDATE user_settings 
                                SET theme ='{$theme}'
                                WHERE user_id = '{$user_id}'");
        }
    }

    public function checkUserIfExistByEmail($email){
        $Db = Utility\Database::getInstance();
        $select = $Db->query("SELECT * from user_contact where email_address = '{$email}'")->results();
        if($select && !empty($select)){
            return true;
        }else{
            return false;
        }
    }

    public static function getUserNotifications($user_id) {
        $Db = Utility\Database::getInstance();
        $query = $Db->query("SELECT * 
            FROM user_notifications 
            WHERE user_id = '{$user_id}'
            AND read_flag != 1");
        
        // $data['results'] = $query->results();
        // $data['count'] = $query->count();
        // $data['error'] = $query->error();

        return $data = array(
            'results' => $query->results(),
            'count' => $query->count(),
            'error' => $query->error()
        );
    }

    public function getUserDocumentType($user_id) {
        //$query = "SELECT ISNULL(type, 'OTHER') as type FROM document GROUP BY type";
        $query = "SELECT d.type
        FROM document AS d
        LEFT JOIN shipment AS s
        ON d.shipment_num = s.shipment_num
        WHERE s.user_id = '{$user_id}' GROUP BY d.type";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function getUserContactList($user_id) {
        // $query = "SELECT 
        // sc.address_type,
        // sc.email, 
        // sc.organization_code,
        // sc.is_default,
        // sc.company_name
        // FROM shipment_contacts sc
        // LEFT JOIN shipment s 
        // ON s.id = sc.shipment_id
        // WHERE s.user_id = '{$user_id}'
        // GROUP BY
        // sc.address_type,
        // sc.email, 
        // sc.organization_code,
        // sc.is_default,
        // sc.company_name";
        $query = "SELECT * FROM user_contact WHERE admin_id = '{$user_id}'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function getUserContactInfo($contact_id) {
        $query = "SELECT * FROM user_contact WHERE id = '{$contact_id}'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function updateUserContactInfo(array $fields, $contact_id) {
        // $table = "user_contact";
        // $id = $contact_id;

        // if (count($fields)) {
        //     $x = 1;
        //     $set = "";
        //     $params = [];
        //     foreach ($fields as $key => $value) {
        //         $params[":{$key}"] = $value;
        //         $set .= "{$key} = :$key";
        //         if ($x < count($fields)) {
        //             $set .= ", ";
        //         }
        //         $x ++;
        //     }

        // }
        // echo "UPDATE {$table} SET {$set} WHERE id = {$id}";

        $this->update("user_contact", $fields, $contact_id);



    }

    public function putUserContactInfo(array $fields) {
        $this->create("user_contact", $fields);
        #$this->update("user_contact", $fields, $contact_id);

        // $Db = Utility\Database::getInstance();
        // $query = "INSERT
        //             INTO user_contact (admin_id,user_id,email_address,organization_code,company_name,is)
        //             VALUES('{$user_id}','{$theme}')";
        // return $Db->query()->results();
    }

    public static function getUserInfoByID($user_id, $args = "*") {
        $query = "SELECT * FROM user_info WHERE user_id = '{$user_id}'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function putUserLog($data) {
        $query = "";

        if(Utility\Session::exists('log_hash')){
            $old_hash = Utility\Session::get('log_hash');
            $query .= "UPDATE user_log 
            SET end_date = '" . date("Y-m-d H:i:s") .
            "' WHERE log_hash = '{$old_hash}';";
        } 

        $log_hash = md5(mt_rand(0, 32) . time());

        Utility\Session::put('log_hash', $log_hash);
        
        $data['log_hash'] = $log_hash;

        $column = implode(", ", array_keys($data));
        $values = implode("', '", array_values($data));

        $query .= " INSERT
        INTO user_log ({$column})
        VALUES ('{$values}')";

        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function getIPAddress() {  
        //whether ip is from the share internet  
         if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
            $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }  
        //whether ip is from the proxy  
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
         }  
        //whether ip is from the remote address  
        else{  
            $ip = $_SERVER['REMOTE_ADDR'];  
        }  
        return $ip;  
    }  

        /**
     * Get User Log Instance: Get instance of a specified user log record in the database.
     * @access public
     * @param int $userID
     * @return Db
     * @since 1.0.3
     * @throws Exception
     */
    public static function getUsersLogInstance($userID, $roleID) {

        switch ($roleID) {
            case 1:
                $query = "SELECT user_log.id, 
                    user_info.first_name, 
                    user_info.last_name, 
                    user_role.role_id AS 'role',
                    user_log.ip_address,
                    user_log.log_action AS 'action',
                    DateDiff(second, user_log.start_date, user_log.end_date) AS 'duration'
                    FROM user_log
                    LEFT JOIN users ON users.id = user_log.user_id
                    LEFT JOIN user_info ON  users.id = user_info.user_id
                    LEFT JOIN user_role ON  users.id = user_role.user_id 
                    ORDER BY user_log.id DESC";
                break;
            case 2:
                $query = "SELECT users.id, 
                    user_info.first_name, 
                    user_info.last_name, 
                    user_info.email,
                    user_info.status AS 'status',
                    user_info.city,
                    user_images.image_src,
                    subscription.name AS 'plan' FROM users
                    LEFT JOIN user_info ON  users.id = user_info.user_id
                    LEFT JOIN subscription ON  user_info.subscription_id = subscription.id
                    LEFT JOIN user_images ON user_images.user_id = user_info.user_id
                    WHERE user_info.account_id = {$userID}";
                break;
            case 3:
                $query = "SELECT users.id, 
                    user_info.first_name, 
                    user_info.last_name, 
                    user_info.email,
                    user_info.status AS 'status',
                    user_info.city,
                    user_images.image_src,
                    subscription.name AS 'plan' FROM users
                    LEFT JOIN user_info ON  users.id = user_info.user_id
                    LEFT JOIN subscription ON  user_info.subscription_id = subscription.id
                    LEFT JOIN user_images ON user_images.user_id = user_info.user_id
                    WHERE user_info.account_id = {$userID}";
                break;
            case 4:
                $query = "SELECT users.id, 
                    user_info.first_name, 
                    user_info.last_name, 
                    user_info.email,
                    user_info.status AS 'status',
                    user_info.city,
                    user_images.image_src,
                    subscription.name AS 'plan' FROM users
                    LEFT JOIN user_info ON  users.id = user_info.user_id
                    LEFT JOIN subscription ON  user_info.subscription_id = subscription.id
                    LEFT JOIN user_images ON user_images.user_id = user_info.user_id
                    WHERE user_info.account_id = {$userID}";
                break;
            default:
                # code...
                break;
        }
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public static function getUserMenu($role_id = "") {
        $query = "SELECT * FROM menu WHERE role_id = '{$role_id}' AND show = 1";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function getSubAccountInfo($user_id = "") {
        $query = "SELECT * FROM vrpt_subaccount WHERE user_id = '{$user_id}'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

}