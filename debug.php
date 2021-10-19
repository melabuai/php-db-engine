<?php

/**
 * DEBUG (debug.php)
 * 
 * Here are the settings for the error messages.
 * 
 * The error messages should be off on a production live server.
 * 
 * The debug mode is intended for the local environment.
 */

// DEBUG MODE (if true, the debug console is displayed)
define('DEBUG', true);

// Set error reporting
if (DEBUG == true) {

    ini_set('html_errors', 1);
    ini_set('error_reporting', -1); // This corresponds to error_reporting (E_ALL);
    ini_set('display_errors', 1);

    error_reporting(-1); // Report all PHP errors
    
} else {

    ini_set('html_errors', 0);
    ini_set('error_reporting', 0);
    ini_set('display_errors', 0);

    error_reporting(0); // Switch off error reporting completely
}
