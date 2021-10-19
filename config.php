<?php

/**
 * SETTINGS (config.php)
 * 
 * Here we can firmly define the variables we need.
 * 
 * At the moment there is only the data for the database.
 * 
 * However, SMTP and other data can also be defined here.
 */

// ALL DBs
define('DB_CHARSET', 'utf8'); // DB Charset
define('DB_HOST', "127.0.0.1"); // DB Host
define('DB_PORT', "3306"); // DB Port
define('DB_NAME', "dbname"); // DB Name
define('DB_USER', "root"); // DB Username
define('DB_PASS', "password"); // DB Password
// PDO ONLY
define('DB_PDO_DRIVER', 'mysql'); // User other driver if you need
define('DB_DSN', DB_PDO_DRIVER . ':host=' . DB_HOST . ':' . DB_PORT . ';dbname=' . DB_NAME);