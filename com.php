<?php

/**
 * COMMONS (com.php)
 * 
 * The commons file is used to load everything we need, so in this case it replaces an autoloader.
 * 
 * 1. The first thing to do is debug so that we can identify errors. there should be nothing else in front of it.
 *
 * 2. Then we load our configuration so that we have all of our settings.
 * 
 * 3. Finally comes the database engine that we load.
 */
require_once 'debug.php';
require_once 'config.php';
require_once 'classes/db/class.DB_ENGINE.php';
