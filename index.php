<?php

header('Content-type: application/json; charset=utf-8');

require_once "./config.php";

//$router = new Router($routes);
//$router = new \Klein\Klein();
$router = new FusionRouter();

require_once "./router.php";

$router->dispatch();
/*
$router->identifyRoute();

$router->createUrl(PAGE_FOLDER);

$router->requestDestination($_POST);
*/
