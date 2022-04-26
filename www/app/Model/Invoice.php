<?php

namespace App\Model;

use Exception;
use App\Core;
use App\Utility;

use GuzzleHttp\Psr7;

/**
 * Invoice Model:
 *
 * @author John Alex
 * @since 1.0.7
 */
class Invoice extends Core\Model {

    /** @var Invoice */
    private static $_Invoice = null;

    public static function getInstance() {
        if (!isset(self::$_Invoice)) {
            self::$_Invoice = new Document();
        }
        return(self::$_Invoice);
    }

    public static function getInvoice($user_id, $arg) {
    }

    public static function getInvoiceData($param, $arg = "*") {
        $Db = Utility\Database::getInstance();
        $query = "SELECT {$arg}
            FROM shipment_arinvoice 
            WHERE user_id = '{$param['user_id']}' 
            AND shipment_num = '{$param['shipment_num']}'";

        $query_total = $Db->query($query)->count();

        if(!empty($param)) {
            $array = array(
                0 => 'id',
                1 => 'status',
                2 => 'saved_date',
                3 => 'message',
            );

            $query .=  " ORDER BY ".$array[$param['order'][0]['column']]." ".$param['order'][0]['dir']." 
            OFFSET ".$param['start']." ROWS 
            FETCH NEXT ".$param['length']." ROWS ONLY";
        }

        // // $query = "SELECT {$column} FROM document WHERE shipment_num = '{$shipment_num}'";
        // if(!empty($group_by)) {
        //     $query .= ' GROUP BY ' . $group_by;
        // }

        $response = array(
            "draw"            => $param['draw'], 
            "recordsTotal"    => $query_total, 
            "recordsFiltered" => $query_total, 
            "data"            => $Db->query($query)->results()
        );

        return $response;
    }

}