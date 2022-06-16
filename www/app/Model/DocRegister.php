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
        $dateuploaded = date('Y-m-d H:i:s');
        $status = 'processing';
        $Db = Utility\Database::getInstance();
        $query = "INSERT INTO match_registration(user_id,filename,filepath,dateuploaded,uploadedby,status)
                   VALUES('{$userid}','{$filename}','{$filepath}','{$dateuploaded}','{$uploadedby}','{$status}')
                 ";
         return $Db->query($query);      
    }


    public function getLastID(){
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT IDENT_CURRENT('match_registration') as lastid")->results();
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
    //need to put this in one function
    public function getcwresponse($prim_ref){
        $Db = Utility\Database::getInstance();
        $query = "SELECT cw_response FROM match_report_reg WHERE prim_ref = '{$prim_ref}' ";
        
        return  $Db->query($query)->results();
    }

    public function addToCGM_Response($data){
        $cgm = $data['cgm'];
        $prim_ref = $data['prim_ref'];
        $Db = Utility\Database::getInstance();
        // $query = "INSERT
        //     INTO match_report(cgm_response)
        //     VALUES('{$data}')
        // ";
        
        $query = "UPDATE match_report_reg 
               SET cgm_response = '{$cgm}'
               WHERE prim_ref = '{$prim_ref}'";
        return  $Db->query($query);
    }

    //need to put this in one function 
    public function getCGMresponse($prim_ref){
        $Db = Utility\Database::getInstance();
        $query = "SELECT cgm_response FROM match_report_reg WHERE prim_ref = '{$prim_ref}' ";
        
        return  $Db->query($query)->results();
    }

    public function getListCount($user_id){
        $Db = Utility\Database::getInstance();
        $count = array();
        $countNew = $Db->query("SELECT count(process_id) as count 
                       FROM match_registration WHERE user_id = '{$user_id}' AND dateuploaded >= DATEADD(day, -30, GETDATE())")->results();
        $countprocessing = $Db->query("SELECT count(process_id) as count
                            FROM match_registration mr 
                            LEFT JOIN
                            match_report_reg mreg ON mreg.prim_ref = mr.process_id 
                            WHERE user_id = '{$user_id}' AND mreg.prim_ref is null")->results();                         
        $countCompleted = $Db->query("SELECT count(prim_ref) as count 
                            FROM match_report_reg mreg LEFT JOIN match_registration mr on mr.process_id = mreg.prim_ref
                            WHERE cw_response_status  = 'Success' AND mr.user_id='{$user_id}'")->results();
        $countFailed = $Db->query("SELECT count(prim_ref) as count 
                                    FROM match_report_reg mreg LEFT JOIN match_registration mr on mr.process_id = mreg.prim_ref
                                    WHERE cw_response_status  = 'Failed' AND mr.user_id='{$user_id}'")->results();
        $countArchive = $Db->query("SELECT count(process_id) as count from match_registration_archive where user_id='{$user_id}'")->results();
        
        $count['new'] = $countNew[0]->count;
        $count['processing'] = $countprocessing[0]->count;
        $count['completed'] = $countCompleted[0]->count;
        $count['failed'] = $countFailed[0]->count;
        $count['archive'] = $countArchive[0]->count;

        return $count;

    }

    public function chartData($user_id){
        $Db = Utility\Database::getInstance();
        $query = "select 
            count( CAST(dateuploaded AS DATE)) as countdate,
            CAST(dateuploaded AS DATE) as DateField
            from match_registration 
            where user_id = '{$user_id}'
            and 
            dateuploaded >= DATEADD(day, DATEDIFF(day, 0, DATEADD(month, -1, CURRENT_TIMESTAMP)), 0)
            Group by CAST(dateuploaded AS DATE)";
        
        return $Db->query($query)->results();
    }

    public function getOrgCodeByUserID($user_id) {
        $query = "SELECT * FROM organization WHERE user_id = '{$user_id}' and consignee = 'Y'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function getShipCodeByUserID($user_id) {
        $query = "SELECT * FROM organization WHERE user_id = '{$user_id}' and consignee = 'N'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function insertLogs($data){
        $processid = $data['process_id'];
        $user_id = $data['user_id'];
        $timestamp = date('Y-m-d H:i:s');
        $status = $data['status'];
        $logs = $data['logs'];
        $response = $data['response'];
        $action = $data['action_type'];

        $Db = Utility\Database::getInstance();
        $query = "INSERT 
                INTO match_report_reg_logs(process_id,user_id,timestamp,status,logs,match_report_response,action_type)
                VALUES('{$processid}','{$user_id}','{$timestamp}','{$status}','{$logs}','{$response}','{$action}')
                ";
        return $Db->query($query);
    }
}