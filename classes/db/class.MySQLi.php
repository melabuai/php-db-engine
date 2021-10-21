<?php

namespace System\Database;

/**
 * MySQLi (Provider-specific database extensions)
 * 
 * @author      prod3v3loper
 * @copyright   (c) 2021, Samet Tarim
 * @link        https://www.prod3v3loper.com
 * @package     melabuai
 * @subpackage  db-engine
 * @version     1.0
 * @since       1.0
 */
if (!class_exists('DB_MySQLi')) {

    class DB_MySQLi {

        /**
         * @var Object Database connection
         */
        private $MySQLiObj = null;

        /**
         * @var String Last SQL query
         */
        public $lastSQLQuery = '';

        /**
         * @var String Status of the last request
         */
        public $lastSQLStatus = '';

        /**
         * @var String Last SQL error
         */
        public $lastSQLError = '';

        public function __construct() {
            $this->init();
        }

        private function __clone() {
            // Prevents the database from being cloned
        }

        /**
         * Initiates the database
         */
        public function init() {

//            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $this->MySQLiObj = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

            /* Check whether an error has occurred */
            // http://php.net/manual/de/mysqli.errno.php
            if ($this->MySQLiObj->connect_errno) {
                // http://php.net/manual/de/mysqli.error.php
                trigger_error("MySQLi-Connection-Error ERRNO: " . $this->MySQLiObj->connect_errno . " - ERROR: " . $this->MySQLiObj->connect_error . "", E_USER_ERROR);
            }

            /* Check if server is alive */
            // http://php.net/manual/de/mysqli.ping.php
            if (!$this->MySQLiObj->ping()) {
                trigger_error("MySQLi ping - ERROR: " . $this->MySQLiObj->error . "", E_USER_ERROR);
            }

            /* Change charset */
            // http://php.net/manual/de/mysqli.set-charset.php
            if (!$this->MySQLiObj->set_charset(DB_CHARSET)) {
                trigger_error("MySQLi set charset " . DB_CHARSET . " - ERROR: " . $this->MySQLiObj->error . "", E_USER_ERROR);
            }

            // Character set of connection
            if (!$this->query("SET NAMES " . DB_CHARSET)) {
                trigger_error("MySQLi set charset names: " . DB_CHARSET . " - ERROR: " . $this->MySQLiObj->error . "", E_USER_ERROR);
            }
        }

        /**
         * @return Object MySQLI instance
         */
        public function getConnection() {
            return $this->MySQLiObj;
        }

        /**
         *  Executes an SQL query
         * 
         *  The optional parameter determines whether the result is returned as an array structure or as a normal MySQL result set
         * 
         *  @param String The SQL query
         *  @param Boolean Parameter, whether a result set or an array should be returned
         *
         *  @return Array Does not return a result set
         */
        public function query($sqlQuery, $resultset = false) {

            // Record last SQL query
            $this->lastSQLQuery = $sqlQuery;

            // Logs all database access
            $this->doLogSQLquery($sqlQuery);

            /**
             * Execute SQL command 
             * @see http://php.net/manual/de/mysqli.query.php
             */
            $result = $this->MySQLiObj->query($sqlQuery);

            // Return the result as a MySQL result "plain" Result
            if ($resultset == true) {
                // Error
                if ($result == false) {
                    $this->lastSQLStatus = false;
                } else {

                    $this->lastSQLStatus = true;
                }

                return $result;
            }

            // Passed to make Array Result
            $return = $this->makeArrayResult($result);

            return $return;
        }

        /**
         *  Array structure of the request
         * 
         *  Makes a result look like DBX's
         * 
         *  @param DB_MySQLi The result object of a MySQL query 
         * 
         *  @return Boolean/Array Returns either true, false, or a result set
         */
        private function makeArrayResult($ResultObj) {

            if ($ResultObj === false) {

                // Error occurred (e.g. primary key already available)
                $this->lastSQLStatus = false;
                return false;
            } else if ($ResultObj === true) {

                // UPDATE - INSERT etc. only TRUE is returned.
                $this->lastSQLStatus = true;

                return true;
            } else if ($ResultObj->num_rows == 0) {

                // No result of a SELECT, SHOW, DESCRIBE or EXPLAIN statement
                $this->lastSQLStatus = true;

                return array();
            } else {

                $array = array();

                while ($line = $ResultObj->fetch_array(MYSQLI_ASSOC)) {
                    // Lowercase all identifiers in $line
                    array_push($array, $line);
                }

                // Set the status of the request
                $this->lastSQLStatus = true;

                // The array now looks exactly like the result from dbx
                return $array;
            }
        }

        /**
         * 
         * @param String $sqlQuery
         * @param Array $params
         * 
         * @return Boolean
         */
        public function preparedStatement($sqlQuery, $params = array()) {
            /**
             * Create a prepared statement 
             * e.g. SELECT District FROM City WHERE Name=? 
             */
            $stmt = $this->MySQLiObj->prepare($sqlQuery);

            /* bind parameters for markers */
            $keys = '';
            $vals = '';
            foreach ($params as $key => $val) {
                $keys .= $key;
                $vals .= $val . ',';
            }
            $stmt->bind_param($keys, "$vals");

            /* execute query */
            $stmt->execute();

            /* bind result variables */
            $stmt->bind_result($result);

            /* fetch value */
            $stmt->fetch();

            return $result;
        }

        /**
         *  Database logger
         * 
         *  Logs all database access
         * 
         *  @param String Expected parameter: An SQL QUERY that is to be "logged".
         */
        private function doLogSQLquery($sqlQuery) {

            $modusSQLquery = "0";
            $checkSQLmodus = array(
                "SELECT" => "1",
                "DELETE" => "2",
                "SET NAMES " => "3",
                "INSERT" => "4",
                "UPDATE" => "5",
                "DESCRIBE" => "6",
                "TURNECATE" => "7"
            );

            foreach ($checkSQLmodus as $searchSQLmodus => $getSQLmodus) {
                if (stripos($sqlQuery, $searchSQLmodus) !== false) {
                    $modusSQLquery = $getSQLmodus;
                }
            }

//            $insertDBlogSQL = "INSERT INTO `DBLog` (SQLs, Date, Name, Modus) VALUES ('" . $this->MySQLiObj->real_escape_string($sqlQuery) . "', 
//                                                " . time() . ", 
//                                               '" . $this->MySQLiObj->real_escape_string(session_name()) . "', 
//                                               '" . $this->MySQLiObj->real_escape_string($modusSQLquery) . "')";
//
//            $this->MySQLiObj->query($insertDBlogSQL);
        }

        /**
         *  Error message from the last query
         * 
         *  The last error message is entered in the DB
         */
        public function lastSQLError() {

            $this->lastSQLError = $this->MySQLiObj->error;

//            $insertDBerrorSQL = "INSERT INTO `DBerror` (Errno, Error, State, Time) VALUES ('" . $this->MySQLiObj->real_escape_string($this->MySQLiObj->errno) . "', 
//                                                 '" . $this->MySQLiObj->real_escape_string($this->MySQLiObj->error) . "', 
//                                                 '" . $this->MySQLiObj->real_escape_string($this->MySQLiObj->sqlstate) . "', 
//                                                 '" . $this->MySQLiObj->real_escape_string(time()) . "')";
//
//            $this->MySQLiObj->query($insertDBerrorSQL);
        }

        /**
         *  @see http://php.net/manual/de/mysqli.real-escape-string.php
         *
         *  @param String Attribute value
         *
         *  @return String Returns the transferred value masked
         */
        public function escapeString($value) {
            return $this->MySQLiObj->real_escape_string($value);
        }

        /**
         *  @see http://php.net/manual/de/mysqli.affected-rows.php
         * 
         *  @return Integer Returns the number of columns traversed
         */
        public function affectedRows() {
            return $this->MySQLiObj->affected_rows;
        }

        /**
         *  @see http://php.net/manual/de/mysqli.insert-id.php
         * 
         *  @return Integer Returns the last ID
         */
        public function insertID() {
            return $this->MySQLiObj->insert_id;
        }

        /**
         *  Terminates the connection to the database after terminating a script
         */
        public function __destruct() {
            $this->MySQLiObj->close();
        }

    }

}
