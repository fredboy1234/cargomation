<?php

namespace App\Model;

use Exception;
use App\Core;
use App\Utility;

/**
 * APinvooce Model:
 *
 * @author John Alex
 * @since 1.0.7
 */
class Order extends Core\Model {

    /** @var Database */
    private static $Order = null;

    public static function getInstance() {
        if (!isset(self::$Order)) {
            self::$Order = new Order();
        }
        return(self::$Order);
    }

    public function getActiveOrdersCount($user_id){
        $Db = Utility\Database::getInstance();
        $query = "SELECT count(*) as cnt FROM orders WHERE user_id = '{$user_id}' AND status <> 'DLV' ";
        return $Db->query($query)->results();
    }

    public function getPendingOrder($user_id){
        $Db = Utility\Database::getInstance();
        $query = "SELECT count(*) as cnt FROM orders 
           WHERE user_id = '{$user_id}' 
           AND status not in ('SHP','DLV','INA') ";
        return $Db->query($query)->results();
    }
}