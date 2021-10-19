<?php

namespace System\Database;

/**
 * DB ENGINE FACTORY
 * 
 * Factory layer for databases.
 * Connects to the database and encapsulates all requests to the database.
 * Usable with various databases (MySQL, SQLite, PGSQL, Oracle, etc.)
 * 
 * @author      prod3v3loper
 * @copyright   (c) 2021, Samet Tarim
 * @link        https://www.prod3v3loper.com
 * @package     melabuai
 * @subpackage  db-engine
 * @version     1.0
 * @since       1.0
 */
if (!class_exists('DB_ENGINE')) {

    class DB_ENGINE {

        /**
         * The factory method assembles our database.
         * 
         * The namespace, class name and file name must match.
         * Whereby the file is composed as follows `class.` and then the name e.g. MySQLi.php.
         * 
         * @param String $typ
         * @return Object
         */
        public static function factory($typ = '') {

            $return = false;

            // We check if string not empty
            if ($typ != '') {
                //
                // String exists build filename
                $file = 'classes/db/class.' . $typ . '.php';
                //
                // Check if file exists
                if (file_exists($file)) {
                    //
                    // File exists build namespace and add DB_ prefix to prevent for PDO
                    $class = 'DB_' . $typ;
                    $namespaceClass = __NAMESPACE__ . '\\' . $class;
                    //
                    // Require file
                    require_once 'class.' . $typ . '.php';
                    //
                    // Create and return instance of the class, with namespace in this file here
                    $return = new $namespaceClass();
                    //
                } else {
                    echo 'The file with name ' . $file . ' not found ';
                }
            } else {
                echo 'No database class found with ' . $typ;
            }

            return $return;
        }

    }

}
