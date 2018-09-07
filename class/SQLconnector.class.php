<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SQLconnector
 *
 * @author matanasio
 */
class SQLconnector extends PDO {

    public $statement;
    public $resultRow;
    private $availableDrivers;
    private $driver;
    private $sql;

    public static function Instance($host, $user, $pass, $db)
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new SQLconnector($host, $user, $pass, $db);
        }
        return $inst;
    }

    public function __construct($host, $user, $pass, $db) {

        $this->getDrivers();
        
        if ($this->driverExist("sqlsrv")) {
            $this->driver = "sqlsrv";
        } else if ($this->driverExist("odbc")) {
            $this->driver = "odbc";
        } else {
            Router::error("Missing driver for connection", "Database Driver");
        }

        try {
            switch ($this->driver) {
                case "sqlsrv":
                    parent::__construct("sqlsrv:Server=$host;Database=$db;MultipleActiveResultSets=false", $user, $pass,array("ConnectionPooling"=>0));
                    break;

                case "odbc":
                    //&arent::__construct('odbc:DRIVER=FreeTDS;SERVERNAME=mssql;DATABASE=' . $db, $user, $pass);
                    parent::__construct("odbc:DRIVER={ODBC driver 13 for SQL Server};Server=$host;Database=$db", $user, $pass);
                    //parent::__construct("odbc:DRIVER={SQL Server Native Client 11.0};Server=$host;Database=$db", $user, $pass);
                    //parent::__construct("odbc:DRIVER={SQL Server};Server=$host;Database=$db", $user, $pass);
                    break;


                default:

                    break;
            }
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            var_dump($ex);
            Router::error($ex->getMessage(), "Database connection", $ex->getCode());
        }
    }

    public static function cookQuery($query) {
        $newQuery = $query;
        $func_args = func_get_args();

        array_shift($func_args);

        foreach ($func_args as $arg) {
            $newQuery = replace_first("{{!}}", $arg, $newQuery);
        }

        return $newQuery;
    }

    public function prepare($sql, $options = NULL) {
        try {
            $this->sql = $sql;
            $this->statement = parent::prepare($sql);
            if ($this->statement != false) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $ex) {
            Router::error($ex->getMessage(), "SQL Prepare Error", $ex->getCode());
        }
    }

    public function execute($parameters = array()) {
        try {
$time_start_pdo = microtime(true);
            if (!$this->statement->execute($parameters)) {;
                Router::error("Error en la ejecucion de query.", "SQL Execute Error");
            }
            $time_end_pdo = microtime(true);
        $execution_time_pdo = ($time_end_pdo - $time_start_pdo);
        Router::debug(array("time" => $execution_time_pdo));
        } catch (PDOException $ex) {
            Router::error($ex->getMessage(), "SQL Execute Error", $ex->getCode());
        }
    }

    public function fetchResult($mode = PDO::FETCH_ASSOC) {
        $this->resultRow = $this->statement->fetch($mode);
        return $this->resultRow;
    }

    public function getRowCount() {
        return $this->statement->rowCount();
    }

    private function getDrivers() {
        $this->availableDrivers = PDO::getAvailableDrivers();
    }

    private function driverExist($driver) {
        foreach ($this->availableDrivers as $driverName) {
            if ($driverName == $driver) {
                return true;
            }
        }
        return false;
    }

    public static function Utf8_ansi($valor = '') {

        $utf8_ansi2 = array(
            "\u00bf" => "¿",
            "\u00c0" => "À",
            "\u00c1" => "Á",
            "\u00c2" => "Â",
            "\u00c3" => "Ã",
            "\u00c4" => "Ä",
            "\u00c5" => "Å",
            "\u00c6" => "Æ",
            "\u00c7" => "Ç",
            "\u00c8" => "È",
            "\u00c9" => "É",
            "\u00ca" => "Ê",
            "\u00cb" => "Ë",
            "\u00cc" => "Ì",
            "\u00cd" => "Í",
            "\u00ce" => "Î",
            "\u00cf" => "Ï",
            "\u00d1" => "Ñ",
            "\u00d2" => "Ò",
            "\u00d3" => "Ó",
            "\u00d4" => "Ô",
            "\u00d5" => "Õ",
            "\u00d6" => "Ö",
            "\u00d8" => "Ø",
            "\u00d9" => "Ù",
            "\u00da" => "Ú",
            "\u00db" => "Û",
            "\u00dc" => "Ü",
            "\u00dd" => "Ý",
            "\u00df" => "ß",
            "\u00e0" => "à",
            "\u00e1" => "á",
            "\u00e2" => "â",
            "\u00e3" => "ã",
            "\u00e4" => "ä",
            "\u00e5" => "å",
            "\u00e6" => "æ",
            "\u00e7" => "ç",
            "\u00e8" => "è",
            "\u00e9" => "é",
            "\u00ea" => "ê",
            "\u00eb" => "ë",
            "\u00ec" => "ì",
            "\u00ed" => "í",
            "\u00ee" => "î",
            "\u00ef" => "ï",
            "\u00f0" => "ð",
            "\u00f1" => "ñ",
            "\u00f2" => "ò",
            "\u00f3" => "ó",
            "\u00f4" => "ô",
            "\u00f5" => "õ",
            "\u00f6" => "ö",
            "\u00f8" => "ø",
            "\u00f9" => "ù",
            "\u00fa" => "ú",
            "\u00fb" => "û",
            "\u00fc" => "ü",
            "\u00fd" => "ý",
            "\u00ff" => "ÿ");

        return strtr($valor, $utf8_ansi2);
    }

}
