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

        $check_user = $Db->query("SELECT *  FROM vrpt_subaccount where  user_id = '{$user}'")->results();
        
        $check_sub_user = $user;
        if(isset($check_user[0]) && isset($check_user[0]->role_id)){
            if($check_user[0]->role_id > 2 ){
                $check_sub_user = $check_user[0]->account_id;
            }
        }
        $user_image = $Db->query("SELECT *
                                FROM user_images where user_id = '{$check_sub_user}'
                                ")->results();
        $user_settings = $Db->query("SELECT colorScheme  FROM user_settings where user_id = '{$check_sub_user}'")->results();

        return [
            "user_info" => $user_info,
            "user_addr" => $user_addr,
            "user_count" => $user_count,
            "account_info" => $account_info,
            "user_image" => $user_image,
            "user_settings"=>$user_settings
        ];
    }


    public function addUserSettings($data) {
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
        $check_user = $Db->query("SELECT *  FROM vrpt_subaccount where  user_id = '{$user}'")->results();
        
        $check_sub_user = $user;
        if(isset($check_user[0]) && isset($check_user[0]->role_id)){
            if($check_user[0]->role_id > 2 ){
                $check_sub_user = $check_user[0]->account_id;
            }
        }
        return $Db->query("SELECT * 
                                FROM user_settings
                                WHERE user_id = '{$check_sub_user}' ")->results();
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
        $img_id = $fields['imageId'];
        //$img_src = $fields['image_src'];
        $Db = Utility\Database::getInstance();
        $Db->query("UPDATE user_images
                set image_type = '' WHERE user_id = '{$fields['user_id']}' and image_type = '{$fields['image_type']}'
        ");
        
        $check = $Db->query("SELECT id  FROM user_images WHERE user_id = '{$fields['user_id']}' and id = '{$img_id}' ")->results();
        
        if(!empty($check)){
            return $Db->query("UPDATE user_images
                set image_type = '{$fields['image_type']}' WHERE user_id = '{$fields['user_id']}'
            ");
        }else{
            
            return $Db->query("INSERT
                            INTO user_images 
                        (user_id,image_type,uploaded,image_src) 
                        values('{$fields['user_id']}','{$fields['image_type']}',GETDATE(),'{$img_src}')
        ");
        }
        
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

    public static function getUserNotifications($user_id, $account_id = "") {
        $Db = Utility\Database::getInstance();

        $query = $Db->query("SELECT * 
            FROM user_notifications 
            WHERE (user_id = '{$user_id}'
            AND account_id = '{$account_id}') 
            AND (read_flag != 1 OR read_flag IS NULL) ORDER BY id DESC");

        return $data = array(
            'results' => $query->results(),
            'count' => $query->count(),
            'error' => $query->error()
        );
    }

    public static function putUserNotifications($data) {

        $column = implode(", ", array_keys($data));
        $values = implode("', '", array_values($data));

        $query = "INSERT
            INTO user_notifications ({$column})
            VALUES ('{$values}')";
        foreach ($data as $key => $value) {
            $key .= $key . ",";
        }
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function getUserDocumentType($user_key, $role_id, $org_code = "") {
        //$query = "SELECT ISNULL(type, 'OTHER') as type FROM document GROUP BY type";
        // $query = "SELECT d.type
        // FROM document AS d
        // LEFT JOIN shipment AS s
        // ON d.shipment_num = s.shipment_num
        // WHERE s.user_id = '{$user_key}'";
        // if($role_id == 4) {
        //     $query .= "AND (s.consignee = '{$org_code}' OR s.consignor = '{$org_code}')";
        // } 
        // $query .= "GROUP BY d.type";

        $query = "SELECT r.typer AS type, ISNULL(cd.description, 'no description yet') AS description
                    FROM (SELECT d.type as typer
                            FROM document d
                            LEFT JOIN shipment AS s
                    ON d.shipment_num = s.shipment_num
                    WHERE s.user_id = '{$user_key}'
                    GROUP BY type ) as r
                LEFT JOIN cargowise_document_type cd
                ON cd.doc_type = r.typer";
        if($role_id == 4) {
            $query .= "AND (s.consignee = '{$org_code}' OR s.consignor = '{$org_code}')";
        } 
        $query .= " ORDER BY typer ASC";

        // if(false) {
        //     $query = "SELECT d.type 
        //     FROM shipment_contacts sc
        //     LEFT JOIN document d
        //     ON d.shipment_id = sc.shipment_id
        //     WHERE sc.email = '{$user_key}'
        //     GROUP BY d.type";
        // }
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
        $query = "SELECT id FROM user_contact 
            WHERE organization_code = '{$fields['organization_code']}' 
            AND email_address = '{$fields['email_address']}'";
        $result = $this->query($query)->results();
        if(empty($result)) {
            // 'NO EXISTED ACCOUNT';
            $this->create("user_contact", $fields);
        } else {
            // 'ACCOUNT EXISTED';
            $contact_id = $result[0]->id;
            $this->update("user_contact", $fields, $contact_id);
        }

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

    public static function getUserMenu_OLD($role_id = "") {
        $query = "SELECT * FROM menu WHERE role_id = '{$role_id}'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public static function getUserMenu($user_id = "", $role_id = "") {
        $Db = Utility\Database::getInstance();
        $query = "SELECT menu FROM user_settings WHERE user_id = '{$user_id}'";
        $result = $Db->query($query)->results();
        if(empty($result[0]->menu)) {
            $query = "SELECT menu FROM menu WHERE role_id = '{$role_id}'";
            $result = $Db->query($query)->results();
        }
        return $result;
    }

    public function getSubAccountInfo($user_id = "") {
        $query = "SELECT * FROM vrpt_subaccount WHERE user_id = '{$user_id}'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    // Users default setting
    public function defaultSettings($user, $role_id){

        $userData = $this->getUserSettings($user);
        $userData = !isset($userData)?json_decode($userData[0]->shipment):array();
        $doc_type = $this->getUserDocumentType($user, $role_id); 

        $json_setting = '/settings/shipment-settings.json';

        if($role_id == 4 && empty($doc_type)) {
            $json_setting = '/settings/sub-shipment-settings.json';
        }

        $defaultSettings = json_decode(file_get_contents(PUBLIC_ROOT.$json_setting));
        
        $defaultCollection = array();
        if(isset($userData) && !empty($userData)){
            foreach($userData as $key => $value){
                $defaultCollection[]=$value->index_value;
            }
        }
        if(!empty($doc_type)){
            $count = 10;
            foreach ($doc_type as $key => $value) {
                array_push($userData, (object)[
                    'index' => strtolower($value->type),
                    'index_name' => $value->type,
                    // 'index_value' => (string)$count++, // Explicit cast
                    'index_value' => strval($count++), // Function call
                    'index_check' => 'true',
                    'index_lvl' => 'document',
                    'index_sortable' => 'false',
                ]);
            }
        } 
        foreach($defaultSettings->table  as $key=> $value){
            if(!empty($defaultCollection)){
                if(!in_array($value->index_value,$defaultCollection)){
                    $value->index_check = 'false';
                    $userData[] = $value;
                } 
            }else{
                $userData[] = $value;
            }
        }

        if(!empty($this->getUserSettings($user)[0]->shipment)){
            return $this->getUserSettings($user)[0]->shipment;
        }else{
            return json_encode($userData);    
        }

    }

    public function getUserInfoByUserID($user_id, $args = "*") {
        $query = "SELECT * FROM user_info WHERE user_id = '{$user_id}'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function putSaveSearch($user_id, $search_title, $data){
        $Db = Utility\Database::getInstance();
        $result = $Db->query("SELECT search FROM user_settings WHERE user_id ='{$user_id}'")->results();
        $query = "";
        if(is_null($result[0]->search) || empty($result[0]->search)) {
            $arr_data = [];
        } else {
            $arr_data = json_decode($result[0]->search);
        }
        array_push($arr_data, array(
            "search_title"=> $search_title,
            "created_date"=> date("d-m-Y H:i:s"),
            "search_query"=> $data
        ));
        $json_data = json_encode($arr_data);
        $query = "UPDATE user_settings SET search='{$json_data}' WHERE user_id ='{$user_id}'";
        return $Db->query($query)->error();
    }

    public function putRecentSearch($user_id, $data){
        $Db = Utility\Database::getInstance();
        $result = $Db->query("SELECT recent FROM user_settings WHERE user_id ='{$user_id}'")->results();
        $query = "";
        
        if(isset($result[0])){
            if(is_null($result[0]->recent) || empty($result[0]->recent)) {
                $arr_data = [];
            } else {
                $arr_data = json_decode($result[0]->recent);
            }
            array_push($arr_data, array(
                "created_date"=> date("d-m-Y H:i:s"),
                "search_query"=> $data
            ));
            $json_data = json_encode($arr_data);
            $query = "UPDATE user_settings SET recent='{$json_data}' WHERE user_id ='{$user_id}'";
        }else{
            $arr_data = [];
            array_push($arr_data, array(
                "created_date"=> date("d-m-Y H:i:s"),
                "search_query"=> $data
            ));
            $json_data = json_encode($arr_data);
            $query = "INSERT INTO user_settings(user_id,recent) VALUES('{$user_id}','{$json_data}')";
        }
        
        return $Db->query($query)->error();
    }

    public function getSaveSearch($user_id) {
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT search, recent FROM user_settings WHERE user_id ='{$user_id}'")->results();
    }

    // Default CW DocumentType
    public function getCWDOcumentType($user_id) {
        $query = "SELECT * FROM cargowise_document_type_{$user_id}";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

}