<?php

class FusionRouter extends \Klein\Klein {

	public function respond($method, $path = '*', $domainRequest = null){
		if(is_string($domainRequest)){

			//todo: add errors when something does not exist

			$domainParameters = explode(":",$domainRequest);
			$domainObject = $domainParameters[0];
			$domainObjectmethod = $domainParameters[1];

			parent::respond($method, $path, function($request) use ($domainObject, $domainObjectmethod){
				require (__DIR__ . '/../' . PAGE_FOLDER . '/' . $domainObject . ".php");

		        $controller = new $domainObject;

		        $call_params = [];

		        $metodoReflexionado = new ReflectionMethod($domainObject, $domainObjectmethod);
		        $metodoReflexionado->invokeArgs($controller, $call_params);
			});

			
		} else if(is_callable($domainRequest)){
			parent::respond($method, $path, $domainRequest);
		}
	}

}