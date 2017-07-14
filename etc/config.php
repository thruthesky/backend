<?php
/**
 *
 * Configuration File
 *
 * @warning do not put any data here that changes laster. ( Do not put dynamic variable here )
 * This file meant to hold static variables only.
 *
 */




$DATABASE_USER          = 'newtalkative';
$DATABASE_PASSWORD      = 'Wc~lynx*,4133';
$DATABASE_NAME          = 'newtalkative';
$DATABASE_PREFIX        = "_";
$DATABASE_HOST          = 'localhost';
$DATABASE_TYPE          = 'mysql';         // 'mysql' | 'sqlite'




$ADMIN_ID               = 'admin';          // This is an admin id.
$ADMIN_IDS              = ['charles', 'admin2', 'thruthesky'];             // Array. Put other admin ids.

$ANONYMOUS_ID           = 'anonymous';      // Anonymous ID.
$TEST_USER_ID           = 'user';           // Test User ID.



$DIR_DATA               = __ROOT_DIR__ . '/data';


/**
 * Default number of items in one page list.
 */
$DEFAULT_NO_OF_PAGE_ITEMS   = 10;           // number


/**
 *
 * Limit the maximum number of items in one page(list/search)
 * If you set it 50, then client cannot get more than 50 items per one search(page). it will response error if it happens.
 */
$MAX_NO_OF_ITEMS           = 100;          // number


/**
 * If true, debug mode enabled.
 *
 * If false, All the debug related code will not run.
 *      - no log will be save.
 *      - no debug data will be printed on the browser ( to user ).
 */
$DEBUG                  = false;

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





/**
 * Minors
 */

$DIR_FILE                           = $DIR_DATA . '/file';


/**
 *
 *
 * Security
 *
 *
 */

/**
 * MAX_REQUEST_LENGTH is to limit the content of request(user input).
 * If the request of all URL params are bigger than MAX_REQUEST_LENGTH, error will be responded.
 * If it is set to 0, then it does not check the MAX_REQUEST_LENGTH.
 *
 * @example
 *      $MAX_REQUEST_LENGTH = 0;
 *      $MAX_REQUEST_LENGTH = 10000;
 *
 */
$MAX_REQUEST_LENGTH = 10240;




////////////////////////////////////////////////////////// DO NOT EDIT BELOW
/**
 * ---------    D O    N O T     E D I T     B E L O W     ----------
 */

$_config = [];

define('DEBUG',                     $DEBUG);
define('DEBUG_LOG_DATABASE',        $DEBUG_LOG_DATABASE);

define('ADMIN_ID',                  $ADMIN_ID);
define('ANONYMOUS_ID',              $ANONYMOUS_ID);
define('TEST_USER_ID',              $TEST_USER_ID);

define('DATABASE_USER',             $DATABASE_USER);
define('DATABASE_PASSWORD',         $DATABASE_PASSWORD);
define('DATABASE_NAME',             $DATABASE_NAME);
define('DATABASE_PREFIX',           $DATABASE_PREFIX);
define('DATABASE_HOST',             $DATABASE_HOST);
define('DATABASE_TYPE',             $DATABASE_TYPE);

define('DIR_DATA',                  $DIR_DATA);
define('DIR_FILE',                  $DIR_FILE);
define('DIR_UPLOAD',                $DIR_FILE . '/upload');
define('DIR_CACHE',                $DIR_FILE . '/cache');

define('__MODEL_DIR__',     __ROOT_DIR__ . '/model');



define('DEFAULT_NO_OF_PAGE_ITEMS',  $DEFAULT_NO_OF_PAGE_ITEMS);
define('MAX_NO_OF_ITEMS',          $MAX_NO_OF_ITEMS);

define('TIME_TO_DELETED_OLD_UNHOOKED_FILE', $TIME_TO_DELETED_OLD_UNHOOKED_FILE * 60);


define('MAX_REQUEST_LENGTH',        $MAX_REQUEST_LENGTH);
