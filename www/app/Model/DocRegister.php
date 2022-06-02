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
class DocRegister extends Core\Model {

    /** @var Database */
    private static $DocRegister = null;

    public static function getInstance() {
        if (!isset(self::$DocRegister )) {
            self::$DocRegister  = new DocRegister();
        }
        return(self::$DocRegister);
    }

    public function getDocReportReg($user_id){
        $Db = Utility\Database::getInstance();
        $query = "SELECT * FROM match_registration mr 
                 inner join match_report_reg mrr on mrr.prim_ref = mr.process_id
                 WHERE mr.user_id = '{$user_id}'";
        return $Db->query($query)->results();
    }
    public function getDocReportRegSingle($user_id, $prim_ref){
        $Db = Utility\Database::getInstance();
        $query = "SELECT mr.filepath as filename,* 
                 FROM match_registration mr 
                 inner join match_report_reg mrr on mrr.prim_ref = mr.process_id
                 WHERE mr.user_id = '{$user_id}' and mrr.prim_ref='{$prim_ref}'";
        return $Db->query($query)->results();
    }
}