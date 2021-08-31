<?php

namespace App\Model;

use Exception;
use App\Core;
use App\Utility;

/**
 * Exchange Model:
 *
 * @author John Alex
 * @since 1.0.7
 */
class Exchange extends Core\Model {

    /** @var Database */
    private static $_Exchange = null;

    public static function getInstance() {
        if (!isset(self::$_Exchange)) {
            self::$_Exchange = new Exchange();
        }
        return(self::$_Exchange);
    }

    public static function getCurrencyList($arg = "*") {

        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT * FROM currency")->results();

    }

    public static function getAllCurrencyList($arg = "*") {

        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT $arg FROM currency 
                            LEFT JOIN currency_rate 
                            ON currency.currency_code = currency_rate.RATECODE ORDER BY currency_rate.EffectiveDate DESC, currency_rate.RATECODE")->results();
    }

    public static function calcExchange($arg = "*", $currency_code = "USD") {
        $Db = Utility\Database::getInstance();
        // echo "SELECT $arg FROM currency 
        //                     LEFT JOIN currency_rate 
        //                     ON currency.currency_code = currency_rate.RATECODE 
        //                     WHERE currency.currency_code = '{$currency_code}' 
        //                     AND currency_rate.EffectiveDate = convert(date, GETDATE())"; die();
        return $Db->query("SELECT TOP 1 $arg FROM currency 
                            LEFT JOIN currency_rate 
                            ON currency.currency_code = currency_rate.RATECODE 
                            WHERE currency.currency_code = '{$currency_code}' 
                            ORDER BY currency_rate.EffectiveDate DESC")->results();
    }



}