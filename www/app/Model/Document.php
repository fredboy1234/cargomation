<?php

namespace App\Model;

use Exception;
use App\Core;
use App\Utility;

use GuzzleHttp\Psr7;

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

    public static function getDocument($document_id, $arg) {

        $column = "*";
        if(isset($arg[6]) || !empty($arg[6])) {
            $column = $arg[6];
        }

        if(is_array($document_id)) {
            $document_id = implode("','", array_column($document_id, 'shipment_num'));
        }

        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT " . $column . "
                                FROM document 
                                LEFT JOIN document_status ON document.id = document_status.document_id
                                WHERE document.id IN ('" . $document_id . "') ")->results();
    }

    public static function putDocument($data) {

        // Sanitize array and implode
        if(is_array($data)) {
            $value = implode("','", array_values($data));
            $column = implode(", ", array_keys($data));
        }

        $Db = Utility\Database::getInstance();
        // echo "INSERT INTO document (" . $column . ") VALUES ('" . $value . "')";
        $Db->query("INSERT INTO document (shipment_id, shipment_num, type, name, saved_by, saved_date, event_date, path, upload_src, is_published)
        VALUES ('" . $data['shipment_id'] . "','" . $data['shipment_num'] . "','" . $data['type'] . "','" . $data['name'] . "', '', getdate(), getdate(),'','" . $data['upload_src'] . "', 'true') ");

        $last_inserted = $Db->query("SELECT @@IDENTITY AS id")->results();

        $Db->query("INSERT INTO document_status (document_id, status) VALUES ('" . $last_inserted[0]->id . "', 'pending')");

        return $last_inserted;
    }

    public static function getDocumentByShipment($shipment_id, $type = "") {

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
                                WHERE document_status.status != 'deleted' 
                                AND document.is_published = 'true'
                                AND shipment_num IN ('" . $shipment_id . "') " . $type)->results();
    }

    public static function getDocumentByShipID($shipment_id, $args = "*") {
        if(is_numeric(strpos($args, "id")))
            $args = str_replace("id", "d.id", $args);
        if( strpos($shipment_id, ',') !== false )
            $shipment_id = implode("','", array_values(explode(",", $shipment_id)));
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT {$args}
                                FROM document AS d
                                LEFT JOIN document_status AS ds ON ds.document_id = d.id
                                WHERE shipment_num IN ('" . $shipment_id . "') ")->results();
    }

    public static function getDocumentByDocID($document_id, $args = "*") {
        if(is_numeric(strpos($args, "id")))
            $args = str_replace("id", "d.id", $args);
        if( strpos($document_id, ',') !== false )
            $document_id = implode("','", array_values(explode(",", $document_id)));
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT {$args}
                                FROM document AS d
                                LEFT JOIN document_status AS ds ON d.id = ds.document_id
                                WHERE d.id IN ('" . $document_id . "') ")->results();
    }

    public static function getDocumentByUserID($user_id, $args = "*") {
        if(is_numeric(strpos($args, "id")))
            $args = str_replace("id", "d.id", $args);
        if(is_numeric(strpos($args, "shipment_num")))
            $args = str_replace("shipment_num", "d.shipment_num", $args);
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT {$args} 
                                FROM shipment AS s
                                LEFT JOIN users AS u ON u.id = s.user_id
                                LEFT JOIN document AS d ON d.shipment_id = s.id
                                LEFT JOIN document_status AS ds ON ds.document_id = d.id
                                WHERE user_id IN (" . $user_id . ") ")->results();
    }

    public static function updateDocumentStatus($data){
        $Db = Utility\Database::getInstance();
        return $Db->query("if exists(select * from document_status where document_id='{$data['doc_id']}')
                            update document_status set status='{$data['doc_status']}' where document_id='{$data['doc_id']}'
                           else
                            insert into document_status (document_id,status) values('{$data['doc_id']}','{$data['doc_status']}')")->results();
    }

    public static function updateDocumentBulk($data){
        $Db = Utility\Database::getInstance();

        //var_dump($data['data']);

        if(is_array($data['data'])) {
            $document_id = implode("','", array_values($data['data']));
            $insert_val = '';
            foreach ($data['data'] as $value) {
                $insert_val .= "(" . $value . ", '" . $data['value'] . "'),";
            }
            $insert_val = rtrim($insert_val,',');
        }

        if($data['group'] == 'status') {
            $query = "IF EXISTS (SELECT * FROM document_status WHERE document_id IN ('{$document_id}'))
            UPDATE document_status SET status='{$data['value']}' WHERE document_id IN ('{$document_id}')
            ELSE
            INSERT INTO document_status (document_id,status) VALUES {$insert_val}";
        } elseif ($data['group'] == 'action') {
            if($data['value'] == 'deleted') {
                $query = "IF EXISTS (SELECT * FROM document_status WHERE document_id IN ('{$document_id}'))
                UPDATE document_status SET status='{$data['value']}' WHERE document_id IN ('{$document_id}')
                ELSE
                INSERT INTO document_status (document_id,status) VALUES {$insert_val}";
            } elseif ($data['value'] == 'push') {

            }
        }
        return $Db->query($query)->results();
    }

    public static function updateDocumentType($data){
        $Db = Utility\Database::getInstance();
        $query = "UPDATE document SET type='{$data['doc_type']}' WHERE id='{$data['doc_id']}'";
        return $Db->query($query)->results();
    }

    public static function putDocumentComment($data) {

        $data['submitted_date'] = date("Y-m-d H:i:s");

        // Sanitize array and implode
        if(is_array($data)) {
            $value = implode("','", array_values($data));
            $column = implode(", ", array_keys($data));
        }

        // check if there is a multiple document_id
        if( strpos($data['document_id'], ",") === false ) {
            $value_string = "('" . $value . "')";
        } else {
            $data_id = explode(",", $data['document_id']);
            $value_array = array();
            foreach ($data_id as $key => $id) {
                $value_array[$key] = "('" . $data['title'] . "', '" . $data['message'] . "', '" . $data['status'] . "'";
                $value_array[$key] .= ", '" . $data['user_id'] . "', '" . $id . "', '" . $data['submitted_date'] . "')";
            };
            
            $value_string = implode(",", $value_array);
        }

        $Db = Utility\Database::getInstance();
        $results = $Db->query("INSERT INTO document_comment (" . $column . ") VALUES " . $value_string )->error();

        if($results === false) 
            return true;

    }

    public static function getDocumentComment($document_id) {
        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT dc.*, CONCAT(u.first_name, ' ', u.last_name) AS name
                                FROM document_comment AS dc
                                LEFT JOIN users AS u ON u.id = dc.user_id
                                WHERE dc.document_id = " . $document_id)->results();
    }

    public static function putDocumentRequest($data) {
        $mail = new SendMail();

        $data['submitted_date'] = date("Y-m-d H:i:s");
        $data['expired_date'] = date("Y-m-d H:i:s", strtotime('+24 hours'));

        // Sanitize array and implode
        if(is_array($data)) {
            $value = implode("','", array_values($data));
            $column = implode(", ", array_keys($data));
        }

        $Db = Utility\Database::getInstance();
        $results = $Db->query("INSERT INTO document_request (" . $column . ") VALUES ('" . $value . "')")->error();

        if(!$results) {
            return $mail->sendRequestMail($data);
        }
    }

    public static function deleteDocumentByDocID($document_id) {
        // if(is_numeric(strpos($args, "id")))
        //     $args = str_replace("id", "d.id", $args);
        if( strpos($document_id, ',') !== false )
            $document_id = implode("','", array_values(explode(",", $document_id)));
        $Db = Utility\Database::getInstance();
        return $Db->query("UPDATE document_status 
                            SET status = 'deleted'
                            WHERE document_id IN ('" . $document_id . "') ")->results();
    }

    public function getDocumentType() {
        $query = "SELECT ISNULL(type, 'OTHER') as type FROM document GROUP BY type";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function getDocumentTypeByUser($user_id) {
        //$count = " count(*) as count";
        // $count = "";
        // $query = "SELECT ISNULL(type, 'OTHER') as type, {$count}
        $query = "SELECT ISNULL(type, 'OTHER') as type
        FROM document
        LEFT JOIN shipment ON shipment.id = document.shipment_id
        WHERE shipment.user_id = '{$user_id}'
        GROUP BY type ";
        // *EXCLUDE SOME DOC TYPE*
        // SELECT ISNULL(type, 'OTHER') as type
        //         FROM document
        //         LEFT JOIN shipment ON shipment.id = document.shipment_id
        //         WHERE shipment.user_id = '101'
        // EXCEPT
        // SELECT   ISNULL(type, 'OTHER') as type
        // FROM document
        // WHERE    type IN ('OTHER', 'TSC', 'TLX')
        //         GROUP BY type
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function getDocumentStatus() {
        // 
    }

    public function getRequestedDocument($user_id, $arg = '*', $group_by = "") {
        $query = "SELECT {$arg}
        FROM document_request
        WHERE request_type = 'new' 
        AND user_id = {$user_id}
        AND (status IS NULL OR status != 'done')";
        if(!empty($group_by)) {
            $query .= " GROUP BY " . $group_by;
        }
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    // update document request 
    public function putRequestedDocument($data, $token) {
        $status = $data['status'];
        $doc_id = $data['doc_id'];
        $query = "UPDATE document_request SET status = '{$status}'
        WHERE token = '{$token}'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function getRequestedStatus($token) {
        $query = "SELECT status
        FROM document_request
        WHERE token = '{$token}'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function getRequestedDocumentByToken($token) {
        $query = "SELECT *
        FROM document_request
        WHERE token = '{$token}'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }    

    public function putRequestedStatus($status, $token) {
        $query = "UPDATE document_request SET status = '{$status}'
        WHERE token = '{$token}'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->error();
    }

    /**
     * Get Doc Stat: Get document stats for specific user.
     * @access public
     * @param int $user
     * @return array|object
     * @since 1.0.3
     * @throws Exception
     */
    public static function getDocumentStats($user, $org_code = "") {
        $Db = Utility\Database::getInstance();

        $where_clause = "dbo.shipment.user_id = '{$user}'";
        if(!empty($org_code)) {
            $where_clause = "dbo.shipment.consignee = '{$org_code}'";
        }

        $total_files = $Db->query(" SELECT
                                        COUNT(dbo.document.id) as count
                                    FROM
                                        dbo.document
                                        INNER JOIN dbo.shipment
                                            ON dbo.document.shipment_id = dbo.shipment.id
                                        INNER JOIN dbo.document_status
                                            ON dbo.document.id = dbo.document_status.document_id
                                    WHERE
                                        {$where_clause}
                                    AND 
                                        dbo.document_status.status != 'deleted' ")->results();
        $pending_files = $Db->query("   SELECT 
                                            COUNT(*) as count 
                                        FROM 
                                            dbo.document
                                            LEFT JOIN dbo.shipment
                                                ON dbo.document.shipment_id = dbo.shipment.id
                                            LEFT JOIN dbo.document_status
                                                ON dbo.document.id = dbo.document_status.document_id
                                        WHERE 
                                            {$where_clause}
                                        AND 
                                            (dbo.document_status.status = 'pending' 
                                        AND 
                                            dbo.document_status.status != 'deleted') ")->results();
        $new_request = $Db->query(" SELECT 
                                        COUNT(*) as count 
                                    FROM 
                                        document_request dr
                                    WHERE 
                                        dr.user_id = '{$user}' 
                                    AND dr.request_type = 'new'")->results();
        $update_request = $Db->query("  SELECT 
                                            COUNT(*) as count 
                                        FROM 
                                            document_request dr
                                        WHERE 
                                            dr.user_id = '{$user}' 
                                        AND dr.request_type = 'edit'")->results();

        return [
            "total_files" => $total_files,
            "pending_files" => $pending_files,
            "new_request" => $new_request,
            "update_request" => $update_request
        ];
    }

    public static function getDocumentTypeByOrg($org_code) {
        //$count = " count(*) as count";
        $count = "";
        $query = "SELECT ISNULL(type, 'OTHER') as type
        FROM document
        LEFT JOIN shipment ON shipment.id = document.shipment_id
        WHERE shipment.consignee = '{$org_code}'
        GROUP BY type ";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    /**
     * Check Document Type: Check document type
     * returns object
     * @access private
     * @param string $filename
     * @param object $file
     * @return object
     * @since 1.10.1
     */
    public function checkDocumentType($filename, $file) {
        $url = 'https://cargomation.com:5000/classify';
        $client = new \GuzzleHttp\Client(['verify' => false ]);
        $response = $client->request('POST', $url, [
            'multipart' => [
                [ 'name' => 'client', 'contents' => 'a2b'],
                [
                    'Content-type' => 'multipart/form-data',
                    'name' => 'file',
                    'contents' => Psr7\Utils::tryFopen($file, 'r'),
                    'filename' => $filename,

                ]
            ],
        ]);

        return json_decode($response->getBody());
    }

    /**
     * Learn Document Type: Learn document type
     * returns object
     * @access private
     * @param string $filename
     * @param object $file
     * @return object
     * @since 1.10.1
     */
    public function learnDocumentType($filename, $file, $type) {
        $url = 'https://cargomation.com:5000/learn';
        $client = new \GuzzleHttp\Client(['verify' => false ]);
        $response = $client->request('POST', $url, [
            'multipart' => [
                [ 'name' => 'type', 'contents' => $type],
                [
                    'Content-type' => 'multipart/form-data',
                    'name' => 'file',
                    'contents' => Psr7\Utils::tryFopen($file, 'r'),
                    'filename' => $filename,

                ]
            ],
        ]);

        return json_decode($response->getBody());
    }

    public function putDocumentRank($doc_id, $json_encode) {
        $query = "INSERT INTO document_rank (document_id,result) VALUES ('{$doc_id}', '{$json_encode}')";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

    public function getDocumentRank($doc_id) {
        $query = "SELECT * FROM document_rank WHERE document_id = '{$doc_id}'";
        $Db = Utility\Database::getInstance();
        return $Db->query($query)->results();
    }

}