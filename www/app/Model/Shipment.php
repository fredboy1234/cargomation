<?php

namespace App\Model;

use Exception;
use App\Core;
use App\Utility;

/**
 * Shipment Model:
 *
 * @author John Alex
 * @since 1.0.7
 */
class Shipment extends Core\Model {

    /** @var Database */
    private static $_Shipment = null;

    public static function getInstance() {
        if (!isset(self::$_Shipment)) {
            self::$_Shipment = new Shipment();
        }
        return(self::$_Shipment);
    }

    public static function getShipment($user_id, $arg = "*") {
        // $api_url = "http://a2bfreighthub.com/eAdaptor/jsoneAdaptor.php?shipment_id=" . $shipment_id;
        // return json_decode(file_get_contents($api_url));

        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT shipment.id as shipment_id, {$arg} 
                                FROM shipment 
                                LEFT JOIN users ON users.id = shipment.user_id
                                WHERE user_id = '{$user_id}' ")->results();

    }

    /**
     * Get Client user assigned shipments.
     */
    public  function getClientUserShipment($user_id, $arg = "*") {

        $Db = Utility\Database::getInstance();
        
        return $Db->query("SELECT shipment.id as shipment_id, {$arg} 
                        FROM shipment 
                        LEFT JOIN shipment_assigned on shipment.id = shipment_assigned.shipment_id
                        FULL OUTER JOIN Merge_Container on shipment.id = Merge_Container.[SHIPMENT ID]
                        WHERE shipment_assigned.id = '{$user_id}'")->results();
    }

    public static function getDocument($shipment_id) {
        // $api_url = "http://a2bfreighthub.com/eAdaptor/jsoneAdaptor.php?shipment_id=" . $shipment_id . "&request=document";
        // return json_decode(file_get_contents($api_url));

        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT * 
                                FROM document 
                                WHERE shipment_num = '{$shipment_id}'")->results();

    }

    public static function getDocumentBySearch($data,$user){
        
        $Db = Utility\Database::getInstance();
        //$where = "WHERE shipment.id is not null ";
        $where = "WHERE users.id = '{$user}' and shipment.shipment_num is not null";
        $origin = '';
        $status ='';

        if($data['shipment_id'] !="" ){
            $where .= " and shipment.shipment_num like '%{$data['shipment_id']}%'";
        }
        if($data['ETA'] != ""){
            $date =  explode(" - ",$data['ETA']);
            $start_date = date_format(date_create($date[0]), "Y/m/d");
            $end_date = date_format(date_create($date[1]), "Y/m/d");
            $where .= " and shipment.eta between '{$start_date}' and '{$end_date}' and shipment.eta <>'1900-01-01 00:00:00'";
        }
        if($data['client_name'] != ""){
            $where .= " and concat_ws('',users.first_name,' ',users.last_name) like '%{$data['client_name']}%'";
        }

        if($data['consignee'] !=""){
            $where .= " and shipment.consignee like '%{$data['consignee']}%'";
        }

        if($data['consignor'] !=""){
            $where .= " and shipment.consignor like '%{$data['consignor']}%'";
        }

        if($data['container'] != ""){
            $where .= " and shipment_container.containernumber  = '{$data['container']}'";
        }
        
        if(isset($data['origin_cargowise']) && isset($data['origin_hub'])){
            $data['origin'] = "('{$data['origin_cargowise']}','{$data['origin_hub']}')";     
        }
        if(isset($data['container_mode']) && !empty($data['container_mode'])){
            $container_mode =implode("','",$data['container_mode']); 
            $where .= " and shipment.container_mode in('{$container_mode}')";
        }

        if(isset($data['master_bill']) && !empty($data['master_bill'])){
            $where .= " and shipment.master_bill  = '{$data['master_bill']}'";
        }

        if(isset($data['house_bill']) && !empty($data['house_bill'])){
            $where .= " and shipment.house_bill  = '{$data['house_bill']}'";
        }
         
        
        //if($data['origin'] != ""){
            // if($data['ETA'] == ""){
            //     $where .= " and document.upload_src = '{$data['origin']}'";
            // }
           //$origin = $data['origin'];
           //$where .= " and document.upload_src in '{$data['origin']}'";
        //}
         $data['transportmode_air'] = (isset($data['transportmode_air']) ? $data['transportmode_air'] : '');
         $data['transportmode_sea'] = (isset($data['transportmode_sea']) ? $data['transportmode_sea']  : '');
        $data['transportmode'] = "('{$data['transportmode_sea']}','{$data['transportmode_air']}')";
        if($data['transportmode'] != ""){
            $where .= " and shipment.transport_mode  in {$data['transportmode']}";
        }
       //$results = array();
        return $Db->query("SELECT
                                shipment.id
                            FROM dbo.users
                            INNER JOIN dbo.shipment
                            ON dbo.users.id = dbo.shipment.user_id
                            FULL OUTER JOIN dbo.shipment_container
                            ON dbo.shipment.id = dbo.shipment_container.shipment_id
                            FULL OUTER JOIN dbo.document
                            ON dbo.shipment.id = dbo.document.shipment_id 
                            FULL OUTER JOIN dbo.document_status
                            ON document.id = document_status.document_id and document_status.status in('pending','approved')
                           {$where} group by shipment.id")->results();
    }  

    public static function shipmentAssign($data,$user){
        $Db = Utility\Database::getInstance();
        $select = $Db->query("SELECT * FROM shipment_assigned  WHERE shipment_id = '{$data['shipment_id']}' and id = '{$data['user_id']}'")->results();
        if(!empty($select)){
            return $Db->query("update shipment_assigned set status='assigned' where shipment_id = '{$data['shipment_id']}' and id = '{$data['user_id']}'");
        }

        return $Db->query("INSERT
                           INTO shipment_assigned (id,user_id,shipment_id,status)
                           VALUES('{$data['user_id']}','{$user}','{$data['shipment_id']}','assigned')");
    }

    public static function shipmentunAssign($data,$user){
        $Db = Utility\Database::getInstance();
        $userid = $data['user_id'];
        $usershipment = $data['shipment_id'];

        return $Db->query("update shipment_assigned set status='unassigned' where shipment_id = '{$usershipment}' and id = '{$userid}'");
    }

    public static function getShipmentByShipID($shipment_id, $args = "*") {
        if( strpos($shipment_id, ',') !== false )
            $shipment_id = implode("','", array_values(explode(",", $shipment_id)));
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT {$args}
                                FROM shipment AS s
                                WHERE shipment_num IN ('" . $shipment_id . "') ")->results();
    }

    public static function getShipmentByDocID($document_id, $args = "*") {
        if(is_numeric(strpos($args, "id")))
            $args = str_replace("id", "d.id", $args);
        if(is_numeric(strpos($args, "shipment_num")))
            $args = str_replace("shipment_num", "d.shipment_num", $args);
        if( strpos($document_id, ',') !== false )
            $document_id = implode("','", array_values(explode(",", $document_id)));
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT {$args} 
                                FROM shipment AS s
                                LEFT JOIN document AS d ON d.shipment_id = s.id
                                LEFT JOIN document_status AS ds ON ds.document_id = d.id
                                WHERE d.id IN ('" . $document_id . "') ")->results();
    }

    public static function getShipmentByUserID($user_id, $args = "*") {
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT {$args} 
                                FROM shipment
                                FULL OUTER JOIN Merge_Container on shipment.id = Merge_Container.[SHIPMENT ID]
                                WHERE user_id = '{$user_id}' ")->results();
    }

    public function getShipmentThatHasUser($user_id){
        $data = array();
        $shc_arr = array();
        $email = array();
        $data['collection'] = array();
        $data['collection']['implode'] = array();
        $data['collection']['in_shipment'] = array();
        $Db = Utility\Database::getInstance();
        $shipment_contact =  $Db->query("SELECT
                        *, s.id as 'shipmentid',
                        case
                            when shipment_assigned.shipment_id is null then 'not-assigned'
                            when shipment_assigned.status ='unassigned' then 'not-assigned'
                            else 'assigned'
                            end as 'shipment_assigned',
                            (select users.id from users where users.email = sc.email) as 'userid'
                        FROM shipment s 
                        left join shipment_contacts sc on s.id = sc.shipment_id 
                        left join shipment_assigned on sc.shipment_id = shipment_assigned.shipment_id
                        where s.user_id = '{$user_id}'")->results();
        $checkShipmentAssigned = $Db->query("SELECT * FROM shipment_assigned WHERE user_id = $user_id")->results();
        
        foreach( $checkShipmentAssigned as $shi){
            $data['collection']['in_id'][] = $shi->id;
            $data['collection']['in_shipment'][] = $shi->shipment_id;
            $data['collection']['in_user'][] = $shi->user_id;
        }

        foreach($shipment_contact as $shc){
            if(!empty($shc->organization_code)){
                $shc_arr[$shc->organization_code][]=$shc;
                if(!empty($shc->userid)){
                    $data['collection']['user_id'][] = $shc->userid;
                    $data['collection']['shipment_id'][] = $shc->shipmentid; 
                    if(!in_array($shc->shipmentid,$data['collection']['in_shipment'])){
                        $data['collection']['implode'][] = "('".$shc->userid."','".$user_id."','".$shc->shipmentid."','assigned')";
                    }
                   
                }
                $email[$shc->organization_code] = $shc->email;
            }  
        }

        //assignd all shipment if client is
        $imploded = implode(",",$data['collection']['implode']);
        $this->insertMultipleShipment($imploded); 
        

        $data['shipment_contact'] =  $shc_arr;
        $data['list_email'] = $email;
        return  $data;
    }

    public function getShipmentLink($user_id){
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT * from shipment_link sl
                            LEFT JOIN shipment s ON s.shipment_num = sl.shipment_num
                            WHERE s.user_id = '{$user_id}'")->results();
        
    } 

    public function insertMultipleShipment($data){
        $Db = Utility\Database::getInstance();
        return $Db->query("INSERT INTO shipment_assigned (id,user_id,shipment_id,status)
                     VALUES $data");
    }

    public static function getShipmentDynamic($user_id, $arg = "*",$condition="") {
        $where = " WHERE shipment.user_id = '{$user_id}' ";
        $top ='';
        $oderby = '';
        if($condition === 'not arrived'){
         $where .= " and shipment.eta > getdate()";
        }

        if($condition ==='air'){
            $where .=" and shipment.transport_mode = 'air' ";
        }

        if($condition ==='sea'){
            $where .=" and shipment.transport_mode = 'sea' ";
        }
        if($condition === 'port'){
            $top .= "TOP(20)";
            $where .=" and shipment.port_loading is not null and shipment.port_discharge is not null ";
            $oderby .=" order by shipment.port_loading asc";
        }

        if($condition == 'containermode'){
            $where .=" and shipment.container_mode != '' ";
        }
        $Db = Utility\Database::getInstance();
        
        return $Db->query("SELECT  shipment.id as shipment_id, {$arg} 
                        FROM shipment 
                        FULL OUTER JOIN Merge_Container on shipment.id = Merge_Container.[SHIPMENT ID]
                        {$where} {$oderby}")->results();
        
        
    }

    public static function countOfPort($user_id){
        $Db = Utility\Database::getInstance();
        return $Db->query("select count(port_loading) as count ,port_loading
        from shipment where shipment.user_id = '{$user_id}' and port_loading is not null or port_loading <>' ' group by port_loading")->results();
    }

}