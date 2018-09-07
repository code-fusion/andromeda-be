<?php

class ObjectMethod {

    public $method;
    public $variables = array();
    public $postVariables = array();

    public function __construct($method = "index", array $variables = array(), array $postVariables = array()) {
        $this->method = $method;
        $this->variables = $variables;
        $this->postVariables = $postVariables;
    }

}
