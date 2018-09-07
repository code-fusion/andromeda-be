<?php

$environment;

if(strrpos($_SERVER['HTTP_HOST'], "localhost") !== FALSE || strrpos($_SERVER['HTTP_HOST'], "10.54.0") !== FALSE){
	$environment = "dev";
} else {
	$environment = "pro";
}

if($environment == "dev"){
	
	error_reporting(E_ALL);
	
} else if($environment == "pro"){
	
	error_reporting(0);
	
}

//PATH
define("BASE", __DIR__ . "/");
define("PAGES_PATH", BASE . "domain/");

//FOLDER
define("PAGE_FOLDER", "domain");

spl_autoload_register(function ($clase) {
    require_once BASE . 'class/' . $clase . '.class.php';
});

require_once BASE . "db.config.php";
require_once BASE . "functions.php";