<?php

/**
 * Factory use:
 * 
 * @param MySQLi - This return the MySQLi Object, with the settings in config.php
 * @param PDO - This return the PDO Object, with the settings in config.php
 * @param SQLite - This return the SQL Lite Object, with the settings in config.php
 * 
 * Use one of this Params and yo get back one of the Objects
 * Have fun :)
 */

require_once 'com.php';

//..

// MSQLI EXAMPLE
echo '<pre>';
$DB_MySQLi = System\Database\DB_ENGINE::factory('MySQLi');
var_dump($DB_MySQLi); // Instance
//var_dump($DB_MySQLi->getConnection()); // Connection object
echo '</pre>';

//..

echo '<pre>';
$DB_PDO = System\Database\DB_ENGINE::factory('PDO');
var_dump($DB_PDO); // Instance
//var_dump($DB_PDO->getConnection()); // Connection object
echo '</pre>';

//..

echo '<pre>';
$DB_SQL_LITE = System\Database\DB_ENGINE::factory('SQLite');
var_dump($DB_SQL_LITE); // Instance
//var_dump($DB_SQL_LITE->getConnection()); // Connection object
echo '</pre>';