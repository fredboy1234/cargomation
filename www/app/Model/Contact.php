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
        $join_clause = "LEFT JOIN user_info ui ON ui.user_id = uc.user_id";
        $query = "SELECT * FROM user_contact uc {$join_clause} WHERE uc.id = {$contact_id}";
        return $Db->query($query)->results();
    }

    public function deleteContactInfo($contact_id) {
        $Db = Utility\Database::getInstance();
        $query = "UPDATE user_contact SET status = 3 WHERE id = {$contact_id}";
        return $Db->query($query)->results();
    }

}