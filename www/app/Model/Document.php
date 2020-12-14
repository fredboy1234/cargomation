<?php

namespace App\Model;

use Exception;
use App\Core;
use App\Utility;

/**
 * Document Model:
 *
 * @author John Alex
 * @since 1.0.7
 */
class Document extends Core\Model {

    /** @var Database */
    private static $_Document = null;

    public static function getInstance() {
        if (!isset(self::$_Document)) {
            self::$_Document = new Document();
        }
        return(self::$_Document);
    }

    public static function getDocument($shipment_id, $type = "") {

        if(is_array($shipment_id)) {
            $shipment_id = implode("','", array_column($shipment_id, 'shipment_num'));
        }

        if (!empty($type)) {
            $type = "AND type = '{$type}'";
        }

        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT * 
                                FROM document 
                                LEFT JOIN document_status ON document.id = document_status.document_id
                                WHERE shipment_num IN ('" . $shipment_id . "') " . $type)->results();
    }

    public static function addDocumentStatus($data){
        $Db = Utility\Database::getInstance();
        return $Db->query("if exists(select * from document_status where document_id='{$data['doc_id']}')
                            update document_status set status='{$data['doc_status']}' where document_id='{$data['doc_id']}'
                           else
                            insert into document_status (document_id,status) values('{$data['doc_id']}','{$data['doc_status']}')")->results();
    }

    public static function getDocumentBase64($document_id) {
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT img_data 
                            FROM document_base64
                            WHERE document_id = '" . $document_id . "'")->results();
    }
}


