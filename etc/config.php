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
$ANONYMOUS_ID          = 'anonymous';      // Anonymous ID.



$DIR_DATA               = __ROOT_DIR__ . '/data';
$DIR_FILE               = __ROOT_DIR__ . '/data/file';

$DATABASE_USER          = 'root';
$DATABASE_PASSWORD      = '7777';
$DATABASE_NAME          = 'backend';
$DATABASE_PREFIX        = "be_";
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
 *      - no debug data will be printed on the browser ( to user ).
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



$TIME_TO_DELETED_OLD_UNHOOKED_FILE = 1440; // minutes. Unhooked files will be deleted after this minutes. 24 hours is good to go.

if ( file_exists( __ROOT_DIR__ . "/etc/my_config.php") ) require __ROOT_DIR__ . "/etc/my_config.php";





////////////////////////////////////////////////////////// DO NOT EDIT BELOW
/**
 * ---------    D O    N O T     E D I T     B E L O W     ----------
 */

$_config = [];

define('DEBUG',                     $DEBUG);
define('DEBUG_LOG_DATABASE',        $DEBUG_LOG_DATABASE);

define('ADMIN_ID',                  $ADMIN_ID);
define('ANONYMOUS_ID',              $ANONYMOUS_ID);

define('DATABASE_USER',             $DATABASE_USER);
define('DATABASE_PASSWORD',         $DATABASE_PASSWORD);
define('DATABASE_NAME',             $DATABASE_NAME);
define('DATABASE_PREFIX',           $DATABASE_PREFIX);
define('DATABASE_HOST',             $DATABASE_HOST);
define('DATABASE_TYPE',             $DATABASE_TYPE);

define('DIR_DATA',                  $DIR_DATA);
define('DIR_FILE',                  $DIR_FILE);
define('DIR_UPLOAD',                $DIR_FILE . '/upload');

define('__MODEL_DIR__',     __ROOT_DIR__ . '/model');



define('DEFAULT_NO_OF_PAGE_ITEMS',  $DEFAULT_NO_OF_PAGE_ITEMS);

define('TIME_TO_DELETED_OLD_UNHOOKED_FILE', $TIME_TO_DELETED_OLD_UNHOOKED_FILE * 60);