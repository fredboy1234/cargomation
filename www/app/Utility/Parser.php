<?php

namespace App\Utility;

use Smalot\PdfParser;

/**
 * Parser:
 *
 * @author John Alex
 * @since 1.10.1
 */
class Parser {

    /** @var Parser */
    private static $_Parser = null;


    /**
     * Get Instance:
     * @access public
     * @return Parser
     * @since 1.0.1
     */
    public static function getInstance() {
        if (!isset(self::$_Parser)) {
            self::$_Parser = new Parser();
        }
        return(self::$_Parser);
    }

    public function parseFile($object, $arg = []) {

        echo 'test';
        //return $object->parseFile($arg);
    }

    public function getText($object) {
        return $object->getText();
    }

}



