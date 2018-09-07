<?php

class ObjectRoute {

    public $obj;
    public $methods;

    public function __construct($obj) {
        $this->obj = $obj;
        $this->methods = new stdClass(); //Just a collection of methods
        return $this;
    }

    public function addDomainRoutes($method = "GET", $addParams = array(), $updateParams = array()) {

        $this->addMethod("getAll");

        if ($method == "GET") {
            $this->addMethod("get", array("id"))
                    ->addMethod("add", $addParams)
                    ->addMethod("update", $updateParams)
                    ->addMethod("delete", array("id"));
        } else if ($method == "POST") {
            $this->addPostMethod("get", array("id"))
                    ->addPostMethod("add", $addParams)
                    ->addPostMethod("update", $updateParams)
                    ->addPostMethod("delete", array("id"));
        }

        return $this;
    }

    public function addMethod($method = "index", array $variables = array(), array $postVariables = array()) {
        $this->methods->$method = new ObjectMethod($method, $variables, $postVariables);
        return $this;
    }

    public function addPostMethod($method, array $postVariables = array()) {
        return $this->addMethod($method, array(), $postVariables);
    }

    public function hasMethod($methodname) {
        foreach ($this->methods as $method) {
            if ($method->method == $methodname) {
                return true;
            }
        }
        return false;
    }

}
