<?php

abstract class DomainObjectSQL {

    protected $conn;
    protected $queries = array();
    protected $returnNames = array();
    protected $errorMsg = array();
    protected $addParams = array();
    protected $updateParams = array();

    public function __construct() {

        global $conn;
        global $queries;

        $this->conn = $conn;
        $this->queries = $queries;
    }

    public function getAll() {

        if (!is_null($this->queries['getAll']) && !is_null($this->returnNames['getAll'])) {
            $this->conn->prepare($this->queries[$this->queries['getAll']]);
            $this->respondCollection(array(), $this->returnNames['getAll']);
        } else {
            $this->errorRespond("getAll not defined for object");
        }
    }

    public function get($id) {

        if (!is_null($this->queries['get']) && !is_null($this->returnNames['get'])) {
            $this->conn->prepare($this->queries[$this->queries['get']]);
            $this->respondObject(array(":id" => $id));
        } else {
            $this->errorRespond("get not defined for object");
        }
    }

    public function delete($id) {
        $this->conn->prepare($this->queries[$this->queries['delete']]);
        $this->respondProcessDone(array(":id" => $id));
    }

    public function add($args) {

        $this->conn->prepareQueryByName($this->queries[$this->queries['add']]);
        $this->respondProcessDone($args);
    }

    public function update($args) {

        $this->conn->prepareQueryByName($this->queries[$this->queries['update']]);
        $this->respondProcessDone($args);
    }

    protected function respond($data) {
        echo SQLconnector::Utf8_ansi(json_encode($data));
    }

    protected function successRespond() {

        $this->respond(array("success" => true));
    }
    
    protected function failureRespond($msg = ""){
        $this->respond(array("success" => false, "error" => $msg));
    }

    protected function errorRespond($msg) {
        Router::error($msg);
    }

    protected function respondCollection($parameters = array(), $name = "") {

        $this->conn->execute($parameters);

        if ($name == "") {
            $name = $this->returnNames['getAll'];
        }

        $objects = array();

        while ($object = $this->conn->fetchResult()) {
            foreach ($object as $key => $info) {
                $object[$key] = trim($info);
            }
            $objects[] = $object;
        }

        $this->respond(array(
            $name => $objects,
            "count" => count($objects)
        ));
    }

    protected function respondObject($parameters = array(), $name = "") {

        $this->conn->execute($parameters);

        if ($name == "") {
            $name = $this->returnNames['get'];
        }

        $object = array();

        $object = $this->conn->fetchResult();
        foreach ($object as $key => $info) {
            $object[$key] = trim($info);
        }

        $this->respond(array($name => $object));
    }

    protected function respondProcessDone($parameters = array()) {

        $this->conn->execute($parameters);
        $this->successRespond();
    }
    
    protected function respondEmptyObject(){
        
        $this->respond(array(
            $this->returnNames["get"] => array()
        ));
        
    }

}

?>