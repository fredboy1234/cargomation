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


}