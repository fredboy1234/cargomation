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
class Apinvoice extends Core\Model {

    /** @var Database */
    private static $APinvoice = null;

    public static function getInstance() {
        if (!isset(self::$APinvoice)) {
            self::$APinvoice = new Apinvoice();
        }
        return(self::$APinvoice);
    }

    public function addToCGM_Response($data){
        
        $Db = Utility\Database::getInstance();
        $query = "INSERT
            INTO match_report(cgm_response)
            VALUES('{$data}')
        ";
        return  $Db->query($query);
    }

    public function getMatchReport($processID){
        
        $Db = Utility\Database::getInstance();
        $query = "SELECT match_report FROM match_report where prim_ref ='{$processID}' ";
        return  $Db->query($query)->results();
    }

    public function getInvoices($user_id){
        $Db = Utility\Database::getInstance();
        $query = "SELECT * FROM match_apinvoice WHERE user_id = '{$user_id}'";
        return  $Db->query($query)->results();
    }

    public function getInvoicesSuccess(){
        $Db = Utility\Database::getInstance();
        $query = "SELECT * FROM match_report ";
        return  $Db->query($query)->results();
    }

    public function getMatchReportWidthID($prim_ref){
        $Db = Utility\Database::getInstance();
        $query = "SELECT * FROM match_report WHERE prim_ref = '{$prim_ref}' ";
        return  $Db->query($query)->results();
    }

    public function getSingleInvoice($user_id){
        $Db = Utility\Database::getInstance();
        $query = "SELECT 
            ma.process_id as process_id,
            ma.filename as maAPFIlename,  
            ma.filepath,
            ma.status,
            mr.status,
            *
        FROM match_apinvoice ma
        LEFT JOIN match_report mr
        ON mr.prim_ref = ma.process_id
        WHERE user_id = '{$user_id}'
        ";
        return  $Db->query($query)->results();
    }
    public function insertMatchHeader($data){
        $user_id = $data['user_id'];
        $filename = $data['filename'];
        $filepath = $data['filepath'];
        $uploadedby = $data['uploadedby'];
        $status = 'processing';

        $Db = Utility\Database::getInstance();
        $query = "INSERT INTO 
                    match_apinvoice(user_id,filename,filepath,dateuploaded,uploadedby,status)
                    VALUES('{$user_id}','{$filename}','{$filepath}',getdate(),'{$uploadedby}','{$status}')";
                    
        return  $Db->query($query);
    }
}