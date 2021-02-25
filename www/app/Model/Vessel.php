<?php

namespace App\Model;

use Exception;
use App\Core;
use App\Utility;

/**
 * Vessel Model:
 *
 * @author John Alex
 * @since 1.0.7
 */
class Vessel extends Core\Model {

    /** @var Database */
    private static $_Vessel = null;

    public static function getInstance() {
        if (!isset(self::$_Vessel)) {
            self::$_Vessel = new Vessel();
        }
        return(self::$_Vessel);
    }

    public static function getVessel($user_id, $arg = "*") {
        
        $data = array();
        $data['vessel'] =array();
        $Db = Utility\Database::getInstance();
        $vessel =  $Db->query("SELECT {$arg} 
                                FROM transhipment b
                                LEFT JOIN users ON users.id = b.user_id
                                WHERE b.user_id = '{$user_id}' ")->results();
        if(!empty($vessel)){
            foreach($vessel as $ves){
              $data['container'][$ves->container_number][] = $ves;
            }
        }
        return $data['container'];
    }

    public static function getVesselByNumber($vessel_number,$user_id){
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT * 
                                FROM transhipment b
                                LEFT JOIN users ON users.id = b.user_id
                                WHERE b.user_id = '{$user_id}' AND b.container_number  = '{$vessel_number}' 
                                ORDER BY b.date_track asc
                                ")->results();
    }
}