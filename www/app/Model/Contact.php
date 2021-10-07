<?php

namespace App\Model;

use Exception;
use App\Core;
use App\Utility;

/**
 * Transport Model:
 *
 * @author John Alex
 * @since 1.0.7
 */
class Contact extends Core\Model {

    /** @var Database */
    private static $_Transport = null;

    public static function getInstance() {
        if (!isset(self::$_Transport)) {
            self::$_Transport = new Transport();
        }
        return(self::$_Transport);
    }


    public function getContactInfo($contact_id, $join_clause = "") {
        $Db = Utility\Database::getInstance();
        $join_clause = "LEFT JOIN user_info ui ON ui.user_id = uc.user_id ";
        $join_clause .= "LEFT JOIN user_images i ON i.user_id = uc.user_id AND image_type = 'profile'";
        $query = "SELECT * FROM user_contact uc {$join_clause} WHERE uc.id = {$contact_id}";
        return $Db->query($query)->results();
    }

    public function updateContactInfo($contact_id, $post_data, $join_clause = "") {
        $params = array(); $items = null;
        #$allowed = array("first_name","last_name","email","phone"); 
        // Sanitize array and implode
        if(is_array($post_data)) {
            foreach($post_data as $key => $value) {
                #if (in_array($key , $allowed)) {
                    if ($items) $items .= ', ';
                    $items .= "$key='{$value}'";
                    $params[$key] = $value;
                #}
            }
        }
        if (!$items) die("Nothing to update");
        $Db = Utility\Database::getInstance();
        $query = "UPDATE user_info SET {$items} WHERE user_id = {$post_data['user_id']}";
        return $Db->query($query)->error();
    }

    public function deleteContactInfo($contact_id) {
        $Db = Utility\Database::getInstance();
        $query = "UPDATE user_contact SET status = 3 WHERE id = {$contact_id}";
        return $Db->query($query)->results();
    }

}