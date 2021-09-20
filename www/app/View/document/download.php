<?php
 
if(isset($this->document_id) || isset($_REQUEST["document_id"])){
    $file = json_decode(file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/api/get/document/did/'.$this->document_id.'/name,shipment_num,type'));

    $file_name = $file[0]->name;
    $shipment_num = $file[0]->shipment_num;
    $file_type = $file[0]->type;
    $email = $this->email;

    if(!empty($this->subacc_info)) {
        $email = $this->subacc_info[0]->client_email;
    }

    // URL: https://cargomation.com/filemanager/cto@mail.com/CW_FILE/S00001055/MSC/Coversheet%20-%20S00001055.pdf
    $filepath = "E:/A2BFREIGHT_MANAGER/".$email."/CW_FILE/".$shipment_num."/".$file_type."/" . $file_name;

    // // Get parameters
    // $file = urldecode($_REQUEST["file"]); // Decode URL-encoded string

    //Clear the cache
    clearstatcache();

    /* Test whether the file name contains illegal characters
    such as "../" using the regular expression */
    // if(preg_match('/^[^.][-a-z0-9_.]+[a-z]$/i', $file_name)){

        // Process download
        if(file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$filepath.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            flush(); // Flush system output buffer
            readfile($filepath);
            die();
        } else {
            http_response_code(404);
            die();
        }
    // } else {
    //     die("Invalid file name!");
    //     // echo "File does not exist.";
    // }
} else {
    echo "Filename is not defined.";
}


// // Initialize a file URL to the variable
// $url = 'https://write.geeksforgeeks.org/wp-content/uploads/gfg-40.png';
 
// // Initialize the cURL session
// $ch = curl_init($url);
 
// // Initialize directory name where
// // file will be save
// $dir = './';
 
// // Use basename() function to return
// // the base name of file
// $file_name = basename($url);
 
// // Save file into file location
// $save_file_loc = $dir . $file_name;
 
// // Open file
// $fp = fopen($save_file_loc, 'wb');
 
// // It set an option for a cURL transfer
// curl_setopt($ch, CURLOPT_FILE, $fp);
// curl_setopt($ch, CURLOPT_HEADER, 0);
 
// // Perform a cURL session
// curl_exec($ch);
 
// // Closes a cURL session and frees all resources
// curl_close($ch);
 
// // Close file
// fclose($fp);



// // Initialize a file URL to the variable
// $url = 'FILE_URL_PATH';
 
// // Use basename() function to return the base name of file
// $file_name = basename($url);
  
// // Use file_get_contents() function to get the file
// // from url and use file_put_contents() function to
// // save the file by using base name
// if(file_put_contents( $file_name,file_get_contents($url))) {
//     echo "File downloaded successfully";
// }
// else {
//     echo "File downloading failed.";
// }
 




?>