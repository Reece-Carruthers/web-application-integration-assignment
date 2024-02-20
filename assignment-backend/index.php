<?php

declare(strict_types=1);

ini_set('display_errors',"1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

use App\Database;
use App\Response;
use App\Router;

include 'config/exceptionHandler.php';
include 'config/autoloader.php';
include 'config/settings.php';

set_exception_handler('\config\exceptionHandler');
spl_autoload_register("autoloader");

$response = new Response();
$conferenceDatabase = new Database(CONFERENCE_DATABASE);
$usersDatabase = new Database(USERS_DATABASE);
$data = Router::processRequest($conferenceDatabase, $usersDatabase);
$response->outputJSON($data);