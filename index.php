<?php

header('Content-type: application/json; charset=utf-8');

require_once "./config.php";

require_once "./router.php";

$router = new Router($routes);

$router->identifyRoute();

$router->createUrl(PAGE_FOLDER);

$router->requestDestination($_POST);
