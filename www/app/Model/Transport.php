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
class Transport extends Core\Model {

    /** @var Database */
    private static $_Transport = null;

    public static function getInstance() {
        if (!isset(self::$_Transport)) {
            self::$_Transport = new Transport();
        }
        return(self::$_Transport);
    }

    public static function getTransport($user_id, $arg = "*") {
        // $api_url = "http://a2bfreighthub.com/eAdaptor/jsoneAdaptor.php?shipment_id=" . $shipment_id;
        // return json_decode(file_get_contents($api_url));

        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT b.shipment_id as trans_id, {$arg} 
                                FROM vrpt_Transport b
                                LEFT JOIN users ON users.id = b.user_id
                                WHERE b.user_id = '{$user_id}' ")->results();

    }

    public static function getTransportSSR($data,$user){
        
        $Db = Utility\Database::getInstance();
    
        $where = "WHERE users.id = '{$user}'";
        if($data['shipment_id'] != ''){
            $where .= " and b.shipment_id= '{$data['shipment_id']}'";
        }
        if($data['shipment_num'] != ''){
            $where .= " and b.shipment_num= '{$data['shipment_num']}'";
        }
        if($data['vessel_name'] != ''){
            $where .= " and b.vessel_name = '{$data['vessel_name']}'";
        }
        if($data['container'] != ''){
            $where .= " and b.containernumber = '{$data['container']}'";
        }
        if(isset($data['actual_full_deliver']) && $data['actual_full_deliver'] != ''){
            $where .= " and b.actual_full_deliver = '{$data['actual_full_deliver']}'";           
        }
        if(isset($data['trans_estimated_delivery']) && $data['trans_estimated_delivery'] != ''){
            $where .= " and b.trans_estimated_delivery = '{$data['trans_estimated_delivery']}'";           
        }
        if(isset($data['fcl_unload']) && $data['fcl_unload'] != ''){
            $where .= " and b.fcl_unload = '{$data['fcl_unload']}'";           
        }
        
        if(isset($data['port_transport_booked']) && $data['port_transport_booked'] !=""){
            $where .= " and b.port_transport_booked = '{$data['port_transport_booked']}'"; 
        }

        if(isset($data['fslot_date']) && $data['fslot_date'] !=""){
            $where .= " and b.slot_date = '{$data['fslot_date']}'"; 
        }

        if(isset($data['wharf_gate_out']) && $data['wharf_gate_out'] !=""){
            $where .= " and b.wharf_gate_out = '{$data['wharf_gate_out']}'"; 
        }
         
        if(isset($data['estimated_full_delivery']) && $data['estimated_full_delivery'] !=""){
            $where .= " and b.estimated_full_delivery = '{$data['estimated_full_delivery']}'"; 
        }
         
        if(isset($data['empty_returned_by']) && $data['empty_returned_by'] !=""){
            $where .= " and b.empty_returned_by = '{$data['empty_returned_by']}'"; 
        }

        if(isset($data['empty_readyfor_returned']) && $data['empty_readyfor_returned'] !=""){
            $where .= " and b.empty_readyfor_returned = '{$data['empty_readyfor_returned']}'"; 
        }

        if(isset($data['empty_readyfor_returned']) && $data['empty_readyfor_returned'] !=""){
            $where .= " and b.empty_readyfor_returned = '{$data['empty_readyfor_returned']}'"; 
        }
         
        if(isset($data['trans_book_req']) && $data['trans_book_req'] !=""){
            $where .= " and b.trans_book_req = '{$data['trans_book_req']}'"; 
        }
         
        if(isset($data['trans_actual_deliver']) && $data['trans_actual_deliver'] !=""){
            $where .= " and b.trans_actual_deliver = '{$data['trans_actual_deliver']}'"; 
        }
            
        if(isset($data['trans_deliverreq_from']) && $data['trans_deliverreq_from'] !=""){
            $where .= " and b.trans_deliverreq_from = '{$data['trans_deliverreq_from']}'"; 
        }

        if(isset($data['trans_deliverreq_by']) && $data['trans_deliverreq_by'] !=""){
            $where .= " and b.trans_deliverreq_by = '{$data['trans_deliverreq_by']}'"; 
        }
         
        if(isset($data['trans_delivery_labour']) && $data['trans_delivery_labour'] !=""){
            $where .= " and b.trans_delivery_labour = '{$data['trans_delivery_labour']}'"; 
        }
         
        if(isset($data['trans_wait_time']) && $data['trans_wait_time'] !=""){
            $where .= " and b.trans_wait_time = '{$data['trans_wait_time']}'"; 
        }
     
        return $Db->query("SELECT b.shipment_id as trans_id , *
                            FROM vrpt_Transport b
                            LEFT JOIN users ON users.id = b.user_id
                         {$where}")->results();
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

    public  function getShipmentThatHasUser($user_id){
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

    public function insertMultipleShipment($data){
        $Db = Utility\Database::getInstance();
        return $Db->query("INSERT INTO shipment_assigned (id,user_id,shipment_id,status)
                     VALUES $data");
    }

}
