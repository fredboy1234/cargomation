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
            $where .= " and shipment.shipment_num = '{$data['shipment_id']}'";
        }
        if($data['ETA'] != ""){
            $date =  explode(" - ",$data['ETA']);
            $start_date = date_format(date_create($date[0]), "Y-m-d");
            $end_date = date_format(date_create($date[1]), "Y-m-d");
            $where .= " and shipment.eta between '{$start_date}' and '{$end_date}'";
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
            $where .= " and shipcontainer.containernumber  = '{$data['container']}'";
        }
        if($data['origin'] != ""){
           // $where .= " and document.upload_src = '{$data['origin']}'";
           $origin = $data['origin'];
        }
       //$results = array();
        return $Db->query("SELECT
                                shipment.id
                            FROM dbo.users
                            INNER JOIN dbo.shipment
                            ON dbo.users.id = dbo.shipment.user_id
                            FULL OUTER JOIN dbo.shipcontainer
                            ON dbo.shipment.id = dbo.shipcontainer.shipment_id
                            FULL OUTER JOIN dbo.document
                            ON dbo.shipment.id = dbo.document.shipment_id and  dbo.document.upload_src ='{$origin}'
                            FULL OUTER JOIN dbo.document_status
                            ON document.id = document_status.document_id and document_status.status in('pending','approved')
                           {$where} group by shipment.id")->results();
    }  

    public static function shipmentAssign($data,$user){
        $Db = Utility\Database::getInstance();
        return $Db->query("INSERT
                           INTO shipment_assigned (id,user_id,shipment_id)
                           VALUES('{$data['user_id']}','{$user}','{$data['shipment_id']}')");
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
                                WHERE user_id = '{$user_id}' ")->results();
    }

}
