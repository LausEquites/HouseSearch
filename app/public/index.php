<?php

include __DIR__ . "/../../vendor/autoload.php";

define('APP_ROOT', realpath(__DIR__ . "/.."));


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, content-type, Authorization');

$router = new \Core\Router();
$router->loadRoutes(APP_ROOT . "/backend/config/structure.xml");
$router->setNameSpace("HouseSearch\Controllers");
$router->run();