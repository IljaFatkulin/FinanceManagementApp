<?php

declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');

require_once('../vendor/autoload.php');

use App\MyApplication;
const ROUTE = "/untitled/public";
//const ROUTE = "";

$app = new MyApplication();
$app->run();
