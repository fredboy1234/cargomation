<?php
// Upload directory
$target_dir = "E:/FILE_MANAGER/";
date_default_timezone_set('Australia/Melbourne');
$date = date('m-d-Y-h-i-s-a-');
// Upload file
$target_file = $target_dir . basename($date.$_FILES["file"]["name"]);

$msg = "";
if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
  $msg = "Successfully uploaded";
}else{ 
  $msg = "Error while uploading";
}
echo $msg;
exit; 