<?php

$system = []; // system variable used for holding system data.

if ($_SERVER['REQUEST_METHOD']=='OPTIONS') {
    header('Access-Control-Allow-Origin : *');
    header('Access-Control-Allow-Methods : POST, GET, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Headers : X-Requested-With, content-type');
    exit;
}
header('Access-Control-Allow-Origin: *');	/** For ajax json calling from outside */
header('P3P: CP="NOI ADM DEV COM NAV OUR STP"'); /** cookie share on iframe */



require_once __ROOT_DIR__ . '/core/library.php';
require_once __ROOT_DIR__ . '/etc/helpers.php';
require_once __ROOT_DIR__ . '/etc/defines.php';
require_once __ROOT_DIR__ . '/etc/config.php';
require_once __ROOT_DIR__ . '/etc/external/spyc.php'; // yaml parser




debug_log(">>>>>>>> Backend begins : " . date('r') );
debug_log( $_REQUEST );


/**
 *
 *
 *
 * @warning If the class does not exists, it just don't do anything. Do not even return a value.
 * @attention So, you need to check if the class exists or not before you use a class.
 *
 *
 */
spl_autoload_register( function( $what ) {

    $what = str_replace('\\', '/', $what);
    $path = "$what.php";
    $path = strtolower( $path );
    if ( file_exists( $path ) ) {
        require_once $path;
    }

});

