<?php

$db = array();

if($environment == "dev"){
	$db['host'] = "127.0.0.1";
	$db['user'] = "root";
	$db['pass'] = "12345678";
	$db['db'] = "cf-football-dev";
	
} else if($environment == "pro"){
//	$db['host'] = "localhost";
//	$db['user'] = "para123456";
//	$db['pass'] = "para123456";
//	$db['db'] = "parateenscont";
}

require_once BASE . "tables.php";
require_once BASE . "queries.php";

$conn = new Mysqli_custom($db['host'],$db['user'],$db['pass'],$db['db']);
//$conn = SQLconnector::Instance($db['host'],$db['user'],$db['pass'],$db['db']);
