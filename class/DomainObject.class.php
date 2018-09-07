<?php

abstract class DomainObject {

    protected $conn;
    protected $queries = array();
    protected $returnNames = array();
    protected $errorMsg = array();
    protected $addParams = array();
    protected $updateParams = array();

    public function __construct() {

        global $conn;

        $this->conn = $conn;
    }

    public function getAll() {

        if (!is_null($this->queries['getAll']) && !is_null($this->returnNames['getAll'])) {
            $this->conn->prepareQueryByName($this->queries['getAll']);
            $this->respondCollection($this->returnNames['getAll']);
        } else {
            $this->errorRespond("getAll not defined for object");
        }
    }

    public function get($id) {

        if (!is_null($this->queries['get']) && !is_null($this->returnNames['get'])) {
            $this->conn->prepareQueryByName($this->queries['get'], $id);
            $this->respondObject();
        } else {
            $this->errorRespond("get not defined for object");
        }
    }

    public function delete($id) {
        $this->conn->prepareQueryByName($this->queries['delete'], $id);
        $this->respondProcessDone("Error while deleting {$this->returnNames['get']}.");
    }
    
    public function add(){
     
        $args = func_get_args();
        
        $this->conn->prepareQueryByName($this->queries['add'], $args);
        $this->respondProcessDone("Error while inserting {$this->returnNames["get"]}.");
        
    }
    
    public function update(){
        
        $args = func_get_args();
        
        $this->conn->prepareQueryByName($this->queries['update'], $args);
        $this->respondProcessDone("Error while updating {$this->returnNames["get"]}.");
        
    }

    protected function respond($data) {

        echo json_encode($data);
    }

    protected function successRespond() {

        $this->respond(array("success" => true));
    }

    protected function errorRespond($msg) {
        Router::error($msg);
    }

    protected function respondCollection($name = "") {

        $this->conn->queryCall();
        
        if ($name == "") {
            $name = $this->returnNames['getAll'];
        }

        $objects = array();

        while ($object = $this->conn->queryResult->fetch_assoc()) {
            $objects[] = $object;
        }

        $this->respond(array(
            $name => $objects,
            "count" => count($objects)
        ));
    }

    protected function respondObject($name = "") {

        $this->conn->queryCall();
        
        if ($name == "") {
            $name = $this->returnNames['get'];
        }

        if ($this->conn->queryResult->num_rows > 0) {
            $object = $this->conn->queryResult->fetch_assoc();

            $this->respond(array($name => $object));
        } else {
            $this->respond(array($name => array()));
        }
    }

    protected function respondProcessDone($errorMsg = "Error while processing.") {

        $this->conn->queryCall();
        
        if ($this->conn->queryResult) {
            $this->successRespond();
        } else {
            $this->errorRespond($errorMsg);
        }
    }

}

?>