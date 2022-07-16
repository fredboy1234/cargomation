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
class Order extends Core\Model {

    /** @var Database */
    private static $Order = null;

    public static function getInstance() {
        if (!isset(self::$Order)) {
            self::$Order = new Order();
        }
        return(self::$Order);
    }

    public function getOrderData($user_id,$org){
        $Db = Utility\Database::getInstance();
       if($org === 'main'){
           $query = "SELECT * FROM vrpt_Orders WHERE user_id = '{$user_id}' AND status <>'INA'  "; 
       }else{
         if(isset($_SESSION['ac_id'])){
            $account_id = $_SESSION['ac_id'];
         }
            $query = "SELECT * FROM vrpt_Orders WHERE user_id = '{$account_id}' AND status <>'INA' AND buyer_code = '{$org}'";
       }
        return $Db->query($query)->results();
    }
    public function getActiveOrdersCount($user_id){
        $Db = Utility\Database::getInstance();
        if(isset($_SESSION['ac_id']) && isset($_SESSION['org'] )){
            $user_id = $_SESSION['ac_id'];
            $org = $_SESSION['org'];
            $query = "SELECT count(*) as cnt FROM vrpt_Orders WHERE user_id = '{$user_id}' AND status <> 'DLV' AND buyer_code = '{$org}'";
        }else{
            $query = "SELECT count(*) as cnt FROM vrpt_Orders WHERE user_id = '{$user_id}' AND status <> 'DLV' ";
        }
        
        return $Db->query($query)->results();
    }

    public function getPendingOrder($user_id){
        $Db = Utility\Database::getInstance();
        
        if(isset($_SESSION['ac_id']) && isset($_SESSION['org'] )){
            $user_id = $_SESSION['ac_id'];
            $org = $_SESSION['org'];
            $query = "SELECT count(*) as cnt FROM vrpt_Orders WHERE user_id = '{$user_id}' AND status not in ('SHP','DLV','INA') AND buyer_code = '{$org}' ";
        }else{
            $query = "SELECT count(*) as cnt FROM vrpt_Orders WHERE user_id = '{$user_id}' AND status not in ('SHP','DLV','INA') ";
        }

        return $Db->query($query)->results();
    }

    public function getOrderCountByStatus($user_id,$status){
        $Db = Utility\Database::getInstance();
        if(isset($_SESSION['ac_id']) && isset($_SESSION['org'] )){
            $user_id = $_SESSION['ac_id'];
            $org = $_SESSION['org'];
            $query = "SELECT count(*) as cnt FROM vrpt_Orders WHERE user_id = '{$user_id}' AND status='{$status}' AND buyer_code = '{$org}'";
        }else{
            $query = "SELECT count(*) as cnt FROM vrpt_Orders WHERE user_id = '{$user_id}' AND status='{$status}'";   
        }
        return $Db->query($query)->results();
    }

    public function getFilterButton($user_id){
        $Db = Utility\Database::getInstance();
        if(isset($_SESSION['ac_id']) && isset($_SESSION['org'] )){
            $user_id = $_SESSION['ac_id'];
            $org = $_SESSION['org'];
            $query = "SELECT status_desc,status,
                Case
                    when status = 'INC' Then 1
                    when status = 'PLC' Then 2
                    when status = 'SHP' Then 3
                    when status = 'DLV' Then 4
                    when status =  'CNF' Then 5
                End ordering
                FROM vrpt_Orders 
           WHERE user_id = '{$user_id}' and status_desc <>'' AND buyer_code = '{$org}'
           group by status_desc, status order by ordering asc";


        }else{
            $query = "SELECT status_desc,status,
                Case
                    when status = 'INC' Then 1
                    when status = 'PLC' Then 2
                    when status = 'SHP' Then 3
                    when status = 'DLV' Then 4
                    when status =  'CNF' Then 5
                End ordering
                FROM vrpt_Orders 
           WHERE user_id = '{$user_id}' and status_desc <>'' 
           group by status_desc, status order by ordering asc";

        }     
 
        return $Db->query($query)->results();
    }

    public function getFilterResults($user_id,$status){
        $Db = Utility\Database::getInstance();
        if(isset($_SESSION['ac_id']) && isset($_SESSION['org'] )){
            $user_id = $_SESSION['ac_id'];
            $org = $_SESSION['org'];
            $query = "SELECT * FROM vrpt_Orders 
             WHERE user_id = '{$user_id}' 
             AND status='{$status}' AND buyer_code = '{$org}' "; 
        }else{
            $query = "SELECT * FROM vrpt_Orders 
             WHERE user_id = '{$user_id}' 
             AND status='{$status}' ";       
        }
        return $Db->query($query)->results();
    }

    public function getOrderByShipment($user_id){
        $Db = Utility\Database::getInstance();
        if(isset($_SESSION['ac_id']) && isset($_SESSION['org'] )){
            $user_id = $_SESSION['ac_id'];
            $org = $_SESSION['org'];
            $query = "SELECT * FROM vrpt_Orders ord
           INNER JOIN shipment sh on sh.order_number = ord.order_number
           WHERE sh.user_id = '{$user_id}' AND buyer_code = '{$org}'";
        }else{
            $query = "SELECT * FROM vrpt_Orders ord
           INNER JOIN shipment sh on sh.order_number = ord.order_number
           WHERE sh.user_id = '{$user_id}'";
        }   
        return $Db->query($query)->results();
    }
}
