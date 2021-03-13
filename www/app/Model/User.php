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
     * Get User Instance: Get instance of a specified user record in the database.
     * @access public
     * @param int $userID
     * @return Db
     * @since 1.0.3
     * @throws Exception
     */
    public static function getUsersInstance($userID) {
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT users.id, 
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
                                WHERE user_info.account_id = {$userID}")->results();
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

        $user_image = $Db->query("SELECT *
                                    FROM user_images where user_id = '{$user}'
                                ")->results();

        return [
            "user_info" => $user_info,
            "user_addr" => $user_addr,
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
    public function updateUserSettings($column = "*", $data, $user_id) {

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
        $select = $Db->query("SELECT * from users where email = '{$email}'")->results();
        if($select && !empty($select)){
            return true;
        }else{
            return false;
        }
    }

}