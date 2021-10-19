<?php

namespace System\Database;

/**
 * SQL Lite (File based database)
 * 
 * @author      prod3v3loper
 * @copyright   (c) 2021, Samet Tarim
 * @link        https://www.prod3v3loper.com
 * @package     melabuai
 * @subpackage  db-engine
 * @version     1.0
 * @since       1.0
 */
if (!class_exists('DB_SQLite')) {

    class DB_SQLite {

        /**
         * @var Object SQLite object
         */
        private $SQLliteObj = false;

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
            // Open the database (if necessary, this will be created in the root folder as file)
            $this->SQLliteObj = new \PDO('sqlite:' . DB_NAME, '', '');
        }

        /**
         * @return Object SQLite instance
         */
        public function getConnection() {
            return $this->SQLliteObj;
        }

        /**
         *  Make a request to the database
         * 
         * @param String $sql SQL query to the database
         * @param Array $params Params for execute
         * 
         * @return Array Result set
         */
        public function query($sql, $params = array()) {
            // Create a prepared statement
            $stmt = $this->SQLliteObj->prepareStatement($sql);
            // Execute the prepared statement and return data
            return $this->SQLliteObj->execute($params);
        }

        /**
         *  Returns the last error that occurred
         * 
         *  @return String Error message
         */
        public function lastSQLError() {
            return $this->SQLliteObj->PDO->errorInfo();
        }

    }

}