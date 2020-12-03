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
        return $Db->query("SELECT {$arg} 
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
            $where .= " and shipcontainer.containershipnumber = '{$data['container']}'";
        }
        if($data['origin'] != ""){
           // $where .= " and document.upload_src = '{$data['origin']}'";
        }
       //$results = array();
        return $Db->query("SELECT shipment.shipment_num as ex_shipment_num,*
                            FROM dbo.users
                                INNER JOIN dbo.shipment ON dbo.users.id = dbo.shipment.user_id
                                FULL OUTER JOIN dbo.document ON dbo.shipment.id = dbo.document.shipment_id
                                FULL OUTER JOIN dbo.Merge_Container ON dbo.shipment.id = dbo.Merge_Container.[SHIPMENT ID]
                            -- FROM shipment    
                            -- LEFT JOIN users ON users.id = shipment.user_id
                            -- LEFT JOIN document ON document.shipment_id = shipment.id
                            -- LEFT JOIN shipcontainer ON shipcontainer.shipment_id = shipment.id
                           {$where}")->results();
                           
        // if($data['ETA'] == ""){                   
        //     foreach($query as $value){
        //         if($value->upload_src == $data['origin']){
        //             $results[]=$value;
        //         }
        //     }
        // }else{
        //     $results = $query;
        // }

        //return $results;
    }  

    public static function shipmentAssign($data){
        $Db = Utility\Database::getInstance();
        return $Db->query("INSERT
                           INTO shipment_assigned (user_id,shipment_id)
                           VALUES('{$data['user_id']}','{$data['shipment_id']}')");
    }

}
