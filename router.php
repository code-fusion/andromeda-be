<?php

/*
Here is where you should define the routes of your application

Example:

$router->respond(function() {
	return "Return to every request"
});

*/


$router->respond('GET', '/hola/[:name]', function($request){
	return json_encode(["GET_HELLO" => "WORLD"]);
});

$router->respond('GET', '/hola2/[:name]', function($request){
	return json_encode(["GET_HELLO" => "WORLD2"]);
});

$router->respond('GET', '/testing', 'example:test2');



