<?php

namespace System\Database;

/**
 * PDO (PHP database extension)
 * 
 * @author      prod3v3loper
 * @copyright   (c) 2021, Samet Tarim
 * @link        https://www.prod3v3loper.com
 * @package     melabuai
 * @subpackage  db-engine
 * @version     1.0
 * @since       1.0
 */
if (!class_exists('DB_PDO')) {

    class DB_PDO {

        /**
         * @var Object Database connection object
         */
        private $PDOobj = null;

        /**
         * @var Object Current preparedStatement
         */
        private $stmt = null;

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

            // PDO OPTIONS
            $DB_OPTIONS = array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . DB_CHARSET, // Set database connection to utf-8
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, // Database allow data as an object for ORM
                \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true, // Collects data and sends them together to relieve the DB
                \PDO::ATTR_PERSISTENT => true, // Caching for a single user, that speeds up the whole thing
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION // Error exeptions throw
//            PDO::ATTR_EMULATE_PREPARES => true
            );

            // Try to connect
            try {
                // If the connection is successful, the PDO object is saved in `$this->PDOobj`
                $this->PDOobj = new \PDO(DB_DSN, DB_USER, DB_PASS, $DB_OPTIONS);
            }
            // If an error occurs, the PDOException throws an error
            catch (\PDOException $e) {
                // Error handling (e.g. email to admin)
                die('<div style="color:red;">' . $e->getMessage() . '</div>');
            }
        }

        /**
         * @return Object PDO instance
         */
        public function getConnection() {
            return $this->PDOobj;
        }

        /**
         *  Executes an SQL query.
         * 
         *  @param String The SQL query
         *
         *  @return Array Returns a result set
         */
        public function query($sql) {

            try {
                // Carry out a PDO query
                if (!$pdoStmt = $this->PDOobj->query($sql)) {
                    return false;
                }

                // Is there an empty result set ?
                ( $pdoStmt->rowCount() == 0 ) ? $return = array() : $return = $pdoStmt->fetchAll();

                // Statement close
                $pdoStmt->closeCursor();

                return $return;
            } catch (\PDOException $e) {
                // Error handling (e.g. email to admin)
                echo '<div style="color:red;">' . $e->getMessage() . '</div>';
                return false;
            }
        }

        /**
         *  Creates a "prepared statement"
         * 
         *  @param String Statement with placeholder parameters
         */
        public function prepared($statement) {

            // Prepared Statement vorbereiten
            $this->stmt = $this->PDOobj->prepare($statement);

            if ($this->stmt === false) {
                echo '<div style="color:red;">The prepared statement could not be prepared</div>';
            }
        }

        /**
         *  Executes a previously created prepared statement
         * 
         *  @param Array The parameters for the prepared statement.
         * 
         *  @return Array Result of the request
         */
        public function execute($params = array()) {

            // If no statement has yet been created, this is terminated here.
            if ($this->stmt == null) {

                echo '<div style="color:red;">No statement found</div>';

                return false;
            }

            try {
                // Carry out a PDO request
                $this->stmt->execute($params);

                // If no data came back
                if ($this->stmt->columnCount() == 0) {
                    // Return empty array
                    return array();
                }

                // Otherwise, return the data as an array
                return $this->stmt->fetchAll();
            } catch (\PDOException $e) {

                // Error handling (e.g. email to admin)
                echo '<div style="color:red;">' . $e->getMessage() . '</div>';

                return false;
            }
        }

    }

}