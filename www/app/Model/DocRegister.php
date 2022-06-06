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
        $query = "SELECT mr.filename as dfilename,* FROM match_registration mr 
                 left join match_report_reg mrr on mrr.prim_ref = mr.process_id
                 WHERE mr.user_id = '{$user_id}' and archive is null";
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

    public function insertDoc($data){
        $userid = $data['user_id'];
        $filename = $data['filename'];
        $filepath = $data['filepath'];
        $uploadedby = $data['uploadedby'];
        $status = 'processing';
        $Db = Utility\Database::getInstance();
        $query = "INSERT INTO match_registration(user_id,filename,filepath,uploadedby,status)
                   VALUES('{$userid}','{$filename}','{$filepath}','{$uploadedby}','{$status}')
                 ";
         return $Db->query($query);      
    }


    public function getLastID(){
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT IDENT_CURRENT('match_apinvoice') as lastid")->results();
    }

    public function getMatchReportWidthID($prim_ref){
        $Db = Utility\Database::getInstance();
        $query = "SELECT * FROM match_report WHERE prim_ref = '{$prim_ref}' ";
        return  $Db->query($query)->results();
    }

    public function toArchive($prim_ref){
        $Db = Utility\Database::getInstance();
        $query = "UPDATE match_registration set archive = 1 where process_id = '{$prim_ref}'  ";
       
        return  $Db->query($query);
    }

    public function updateParseInput($prim_ref,$parse_input){
        $Db = Utility\Database::getInstance();
        $query = "UPDATE match_registration set parsed_input = '{$parse_input}' where process_id = '{$prim_ref}'  ";
       
        return  $Db->query($query);
    }
}