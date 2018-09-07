<?php

class Router {

    public $baseFolder;
    public $pageFolder;
    public $basePath;
    public $baseUrl;
    public $routes = array();
    public $objectsRoutes = array();
    public $requestUri;
    public $uriParams = array();
    public $routeId;
    public $objectRouteId;
    public $objectRouteMethod;
    private $url;
    private $filePath;
    private $username;
    private $password;
    private $credentials = false;

    public function __construct($routes) {

        foreach ($routes as $routeName => $route) {
            if (get_class($route) == "Route") {
                $this->routes[] = $route;
            } else if (get_class($route) == "ObjectRoute") {
                $this->objectsRoutes[$routeName] = $route;
            }
        }
        $this->requestUri = $_SERVER['REQUEST_URI'];
        $this->baseFolder = $this->getBasePathFolder();
        $this->uriParams = $this->getUriParams($this->baseFolder, $this->requestUri);
        $this->basePath = $this->getBasePath();
        $this->baseUrl = $this->getBaseUrl();
    }

    public function setCredentials($username, $password) {
        $this->credentials = true;
        $this->username = $username;
        $this->password = $password;
    }

    public function identifyRoute() {

        foreach ($this->routes as $key => $route) {

            if ($route->page == $this->uriParams[0]) {
                $this->routeId = $key;
                break;
            }
        }

        if (is_null($this->routeId)) {

            foreach ($this->objectsRoutes as $name => $route) {
                if ((strtolower($name) == $this->uriParams[0]) && ($route->hasMethod($this->uriParams[1]))) {
                    $this->objectRouteId = $name;
                    $this->objectRouteMethod = $route->methods->{$this->uriParams[1]};
                    break;
                }
            }
        }
    }

    public function requestDestination($postData = array()) {
        
        if ($this->credentials) {
            if (!isset($postData['apiuser']) || !isset($postData['apipass'])) {

                self::error("Username and password required", "Incorrect credentials");

                if ($postData['apiuser'] != $this->username || $postData['apipass'] != $this->password) {
                    self::error("Username and/or password incorrect", "Incorrect credentials");
                }
            }
            unset($postData['apiuser']);
            unset($postData['apipass']);
        }
        
        if (!is_null($this->routeId)) {
            $cRequest = new CurlRequest($this->url);

            $route = $this->routes[$this->routeId];

            $this->checkRouteVariableCount($route, $postData);

            if (count($postData) > 0 || (count($this->uriParams) - 1) > 0) {

                $this->checkRouteVariableNames($route, $postData);

                $cRequest->addPostData($postData);
            }

            $cRequest->render();
        } else {

            $objectRoute = $this->objectsRoutes[$this->objectRouteId];

            $this->checkObjectRouteVariableCount($this->objectRouteMethod, $postData);

            if (count($postData) > 0 || (count($this->uriParams) - 2) > 0) {

                $this->checkRouteVariableNames($this->objectRouteMethod, $postData);
            }

            $this->callMethod($objectRoute, $postData);
        }
    }

    private function checkRouteVariableCount($route, $postData) {

        if (count($postData) != count($route->postVariables)) {
            self::error("Incorrect post data receive", "Bad request",1);
        } else if (count($this->uriParams) - 1 != count($route->variables)) {
            self::error("Incorrect get data receive", "Bad request",2);
        }
    }

    private function checkObjectRouteVariableCount($method, $postData) {

        if (count($postData) != count($method->postVariables)) {
            self::error("Incorrect post data receive", "Bad request",3);
        } else if (count($this->uriParams) - 2 != count($method->variables)) {
            self::error("Incorrect get data receive", "Bad request",4);
        }
    }

    private function checkRouteVariableNames($objectRoute, $postData) {

        $correctPostData = true;

        foreach ($objectRoute->postVariables as $postvariable) {

            if (!isset($postData[$postvariable])) {
                $correctPostData = false;
            }
        }

        if (!$correctPostData) {
            self::error("Incorrect post data receive", "Bad request",5);
        }
    }

    public function createUrl($middleFolder) {

        $this->pageFolder = $middleFolder;

        if (is_null($this->routeId) && is_null($this->objectRouteId)) {
            self::error("Incorrect request", "Bad request",6);
        }

        if (!is_null($this->routeId)) {
            $route = $this->routes[$this->routeId];

            $this->url = $this->baseUrl . $middleFolder . "/" . $route->page . ".php?";

            foreach ($route->variables as $key => $variable) {

                if (isset($this->uriParams[$key + 1])) {
                    $this->url .= $variable . "=" . $this->uriParams[$key + 1] . "&";
                }
            }

            $this->url = rtrim($this->url, '&');
        } else {
            $route = $this->objectsRoutes[$this->objectRouteId];

            $this->url = $this->baseUrl . $middleFolder . "/" . $route->obj . ".php?";
        }
    }

    private function getBaseUrl() {

        return (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http') . "://" . $_SERVER['HTTP_HOST'] . $this->basePath;
    }

    private function getBasePathFolder() {
        $scriptPath = $_SERVER['SCRIPT_NAME'];

        $pos = strpos($scriptPath, "router.php");

        $str = substr($scriptPath, 0, $pos - 1);

        $str = strrev($str);

        $pos2 = strpos($str, "/");

        $str = substr($str, 0, $pos2);
        return strrev($str);
    }

    private function getUriParams($serviceName, $requestUri) {

        $str = $requestUri;

        if(strpos($str,'/router.php') !== false){
            $str = substr($str,0,strpos($str,'/router.php')) . substr($str,strpos($str, '/router.php') + strlen('/router.php'));    
        }

        $servicePos = strpos($str, $serviceName);

        $parameters = substr($str, $servicePos + strlen($serviceName) + 1);

        $params = array();

        $parameters = trim($parameters, "/");

        $paramsCount = substr_count($parameters, "/");

        for ($i = 0; $i <= $paramsCount; $i++) {

            $stop = strpos($parameters, "/");
            if ($stop) {
                $params[] = substr($parameters, 0, $stop);

                $parameters = substr($parameters, $stop + 1);
            } else {
                $params[] = $parameters;
            }
        }

        return $params;
    }

    private function getBasePath() {

        $scriptPath = $_SERVER['SCRIPT_NAME'];

        $pos = strpos($scriptPath, "router.php");

        return substr($scriptPath, 0, $pos);
    }

    private function callMethod($objectRoute, $postData = array()) {


        require (__DIR__ . '/../' . $this->pageFolder . '/' . $objectRoute->obj . ".php");

        $controller = new $objectRoute->obj;

        array_shift($this->uriParams);
        array_shift($this->uriParams);

        $call_params = array_merge($postData, $this->uriParams);

        $metodoReflexionado = new ReflectionMethod($objectRoute->obj, $this->objectRouteMethod->method);
        $metodoReflexionado->invokeArgs($controller, $call_params);
    }

    public static function debug() {

        $args = func_get_args();

        echo json_encode($args);

        die();
    }

    public static function error($msg, $type = "", $code = 0, $extra = array()) {

        $errorResponse = array(
            "error" => 1,
            "errorMsg" => $msg,
            "errorType" => $type
        );

        if ($code != 0) {
            $errorResponse["errorCode"] = $code;
        }

        if (count($extra) > 0) {
            array_push($errorResponse, $extra);
        }

        echo json_encode($errorResponse);

        die();
    }

}
