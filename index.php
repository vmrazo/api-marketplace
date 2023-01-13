<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('content-type: application/json; charset=utf-8');




require_once "controllers/router.controller.php";
require_once "controllers/get.controller.php";
require_once "models/get.model.php";

require_once "controllers/post.controller.php";
require_once "models/post.model.php";

require_once "controllers/put.controller.php";
require_once "models/put.model.php";

$index = new RoutesController();

$index->index();

