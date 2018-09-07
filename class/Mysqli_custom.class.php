<?php

class Mysqli_custom extends mysqli {

    public $query;
    public $queryResult;
    public $queries;

    public function __construct($host, $user, $pass, $db) {
        parent::__construct($host, $user, $pass, $db);

        global $queries;
        $this->queries = $queries;

        if ($this->connect_error) {

            Router::error($this->connect_error, "Database connection", $this->connect_errno);
        }
    }
    
    public function prepareQueryByName($queryName) {

        if(!isset($this->queries[$queryName])){
            Router::error("Query '$queryName' does not exist","Mysql");
        }
        
        $query = $this->queries[$queryName];

        $args = func_get_args();
        array_shift($args);
        $args = array_reverse($args);
        $args[] = $query;
        $args = array_reverse($args);
        
        if(isset($args[1]) && is_array($args[1])){
            $argsaux = $args[1];
            unset($args[1]);
            foreach($argsaux as $arg){
                $args[] = $arg;
            }
        }
        
        $metodoReflexionado = new ReflectionMethod(get_class($this), "prepareQuery");
        $metodoReflexionado->invokeArgs($this, $args);
    }

    public function prepareQuery($query) {
        
        $numArgs = func_num_args() - 1;
        $queryArgs = substr_count($query, "?");
                
        if ($numArgs != $queryArgs) {
            Router::error("Incorrect number of arguments provided", "Request");
        }

        $func_args = func_get_args();

        $debug = array();

        array_shift($func_args);

        foreach ($func_args as $arg) {

            $arg = str_replace("?", "specialcodeforreplace", $arg);
            $query = replace_first("?", $this->real_escape_string($arg), $query);
        }

        $query = str_replace("specialcodeforreplace", "?", $query);
        
        $query = $this->Utf8_ansi($query);

        $this->query = $query;
    }

    public function queryCall() {

        if(!is_null($this->queryResult)){
            $this->freeResult();
        }
        
        parent::query("SET CHARACTER SET utf8");
        
        $result = parent::query($this->query);

        if (!$result) {

            Router::error($this->error, "Database query", array("query" => $this->query));
        }

        $this->queryResult = $result;
    }
    
    public function freeResult(){
        mysqli_free_result($this->queryResult);
        if($this->more_results()){
            $this->next_result();
        }
    }
    
    private function Utf8_ansi($valor='') {

    $utf8_ansi2 = array(
    "\u00c0" =>"À",
    "\u00c1" =>"Á",
    "\u00c2" =>"Â",
    "\u00c3" =>"Ã",
    "\u00c4" =>"Ä",
    "\u00c5" =>"Å",
    "\u00c6" =>"Æ",
    "\u00c7" =>"Ç",
    "\u00c8" =>"È",
    "\u00c9" =>"É",
    "\u00ca" =>"Ê",
    "\u00cb" =>"Ë",
    "\u00cc" =>"Ì",
    "\u00cd" =>"Í",
    "\u00ce" =>"Î",
    "\u00cf" =>"Ï",
    "\u00d1" =>"Ñ",
    "\u00d2" =>"Ò",
    "\u00d3" =>"Ó",
    "\u00d4" =>"Ô",
    "\u00d5" =>"Õ",
    "\u00d6" =>"Ö",
    "\u00d8" =>"Ø",
    "\u00d9" =>"Ù",
    "\u00da" =>"Ú",
    "\u00db" =>"Û",
    "\u00dc" =>"Ü",
    "\u00dd" =>"Ý",
    "\u00df" =>"ß",
    "\u00e0" =>"à",
    "\u00e1" =>"á",
    "\u00e2" =>"â",
    "\u00e3" =>"ã",
    "\u00e4" =>"ä",
    "\u00e5" =>"å",
    "\u00e6" =>"æ",
    "\u00e7" =>"ç",
    "\u00e8" =>"è",
    "\u00e9" =>"é",
    "\u00ea" =>"ê",
    "\u00eb" =>"ë",
    "\u00ec" =>"ì",
    "\u00ed" =>"í",
    "\u00ee" =>"î",
    "\u00ef" =>"ï",
    "\u00f0" =>"ð",
    "\u00f1" =>"ñ",
    "\u00f2" =>"ò",
    "\u00f3" =>"ó",
    "\u00f4" =>"ô",
    "\u00f5" =>"õ",
    "\u00f6" =>"ö",
    "\u00f8" =>"ø",
    "\u00f9" =>"ù",
    "\u00fa" =>"ú",
    "\u00fb" =>"û",
    "\u00fc" =>"ü",
    "\u00fd" =>"ý",
    "\u00ff" =>"ÿ");

    return strtr($valor, $utf8_ansi2);      

}

}
