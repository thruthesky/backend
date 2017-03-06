<?php
/**
 *
 * Configuration File
 *
 * @warning do not put any data here that changes laster. ( Do not put dynamic variable here )
 * This file meant to hold static variables only.
 *
 */


$ADMIN_ID               = 'admin';          // this is admin id.




$DIR_DATA               = './data';

$DATABASE_USER          = 'root';
$DATABASE_PASSWORD      = '7777';
$DATABASE_NAME          = 'backend';
$DATABASE_HOST          = 'localhost';
$DATABASE_TYPE          = 'mysql';         // 'mysql' | 'sqlite'


/**
 * Default number of items in one page list.
 */
$DEFAULT_NO_OF_PAGE_ITEMS   = 10;           // number


/**
 * If true, debug mode enabled.
 *
 * If false, All the debug related code will not run.
 *      - no log will be save.
 *      - no debug data will be printed to user.
 */
$DEBUG                  = true;

/**
 * If 'DEBUG_LOG_FILE_PATH' is not empty, then debug data will not be saved.
 */
$DEBUG_LOG_FILE_PATH    = $DIR_DATA . "/debug.log";

/**
 * Database debug message will be logged
 *
 *  if DEBUG = true & DEBUG_LOG_FILE_PATH has value & DEBUG_LOG_DATABASE = true
 *
 *
 */
$DEBUG_LOG_DATABASE         = true;


if ( file_exists( __ROOT_DIR__ . "/etc/my-config.php") ) require __ROOT_DIR__ . "/etc/my-config.php";





////////////////////////////////////////////////////////// DO NOT EDIT BELOW

define('DEBUG',             $DEBUG);

define('DATABASE_USER',     $DATABASE_USER);
define('DATABASE_PASSWORD', $DATABASE_PASSWORD);
define('DATABASE_NAME',     $DATABASE_NAME);
define('DATABASE_HOST',     $DATABASE_HOST);
define('DATABASE_TYPE',     $DATABASE_TYPE);

define('DIR_DATA',          $DIR_DATA);
define('__MODEL_DIR__',     __ROOT_DIR__ . '/model');

