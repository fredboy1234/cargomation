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
        return $vessel;
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

    public function addSeaPort($data){
        $Db = Utility\Database::getInstance();
        $checkport = $Db->query("SELECT * 
                        FROM sea_ports
                        WHERE port_city LIKE '%{$data['port_city']}%'
                            ")->results();
        if(empty($checkport)){
            return $Db->query("INSERT INTO 
                            sea_ports(port_name,port_city,port_lat,port_long,place_id)
                            VALUES('{$data['port_name']}','{$data['port_city']}','{$data['port_lat']}','{$data['port_long']}','{$data['place_id']}')

            ");       
        }

    }

    public function getSeaPort($portcity){
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT top(1) * 
                            FROM sea_ports
                            WHERE port_city like '%{$portcity}%'
                                ")->results();
    }

    // public function addCountry($data){
    //     $Db = Utility\Database::getInstance();
    //     return $Db->query("INSERT INTO 
    //                         country_info(name,code2,code3,area,region,flag)
    //                         VALUES('{$data['name']}','{$data['code2']}','{$data['code3']}','{$data['area']}','{$data['region']}','{$data['flag']}')

    //         "); 
    // }

    public function getFlag($country){
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT flag 
                            FROM country_info
                            WHERE name like '%{$country}%'
                                ")->results(); 
    }

    public function vesseLyod($container_num,$user_id){
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT vesslloyds 
                            FROM vrpt_Transport
                            WHERE containernumber like '%{$container_num}%' AND user_id = '{$user_id}'
                                ")->results(); 
    }


    public function getSearatesDB($arg = "*"){
        $data = array();
        $data['vessel'] =array();
        $Db = Utility\Database::getInstance();
        $vessel =  $Db->query("SELECT {$arg}, sh.shipment_num     
                                FROM transhipment_searates b
                                LEFT JOIN shipment sh on sh.id = b.trans_id 
                                LEFT JOIN transhipment_onestop tro on tro.Lloyds = sh.vesslloyds
                                where sh.id is not null")->results();
       
        return $vessel;
    }
        
    //for searates api to db
    public static function getVesselV2($user_id='', $arg = "*") {
        
        $data = array();
        $data['vessel'] =array();
        $Db = Utility\Database::getInstance();
        $vessel =  $Db->query("SELECT {$arg} 
                                FROM vrpt_transhipment b where b.user_id = {$user_id}")->results();
        if(!empty($vessel)){
            foreach($vessel as $ves){
              if(!array_key_exists($ves->containernumber,$data['vessel'])){
                $data['vessel'][$ves->containernumber ][] = $ves;
              }
              
            }
        }
        return  $data['vessel'];
    }

    public function checkContainer($data,$arg = "*"){
        $trans_id = strval($data['trans_id']);
        $container_number = strval($data['container_number']);
        $sea_json = $data['json'];
        $track_json = $data['track'];
        $userid = $data['user'];
        $Db = Utility\Database::getInstance();
        $vessel =  $Db->query("SELECT {$arg} 
                                FROM transhipment_searates b
                                where trans_id ={$trans_id} and container_number = {$container_number}")->results();
       
        if(!empty($vessel)){
            $Db->query("UPDATE transhipment_searates 
                                    SET sea_json = '{$sea_json}', '{$track_json}'
                                    WHERE trans_id ={$trans_id} and container_number = {$container_number} ");
        }else{
            $Db->query("INSERT INTO transhipment_searates (trans_id,container_number,sea_json,track_json,user_id)
            values ('{$trans_id}','{$container_number}','{$sea_json}','{$track_json}','{$userid}')");
        }                    
        return $vessel;
    }

    public function getSearatesByID($vessel_number){
        
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT  top(1) *
                        FROM transhipment_searates b
                        where container_number = '{$vessel_number}' ")->results();
    }

    public function status(){
        return array(
            "UNK" => 'Unknown',
            "LTS" => 'Land transshipment',
            "BTS" => 'Barge transshipment',
            "CEP" => 'Container empty to shipper',
            "CPS" => 'Container pickup at shipper',
            "CGI" => 'Container arrival at first POL (Gate in)',
            "CLL" => 'Container loaded at first POL',
            "VDL" => 'Vessel departure from first POL',
            "VAT" => 'Vessel arrival at T/S port',
            "CDT" => 'Container discharge at T/S port',
            "TSD" => 'Transshipment Delay',
            "CLT" => 'Container loaded at T/S port',
            "VDT" => 'Vessel departure from T/S',
            "VAD" => 'Vessel arrival at final POD',
            "CDD" => 'Container discharge at final POD',
            "CGO" => 'Container departure from final POD (Gate out)',
            "CDC" => 'Container delivery to consignee',
            "CER" => 'Container empty return to depot',
        );
    }
}