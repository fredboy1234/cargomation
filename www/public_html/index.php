<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../app/init.php";

$App = new App\Core\App;
$App->run();
