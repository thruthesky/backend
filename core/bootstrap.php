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



require __ROOT_DIR__ . '/core/library.php';
require __ROOT_DIR__ . '/etc/helpers.php';
require __ROOT_DIR__ . '/etc/defines.php';
require __ROOT_DIR__ . '/etc/config.php';





debug_log(">>>>>>>> Backend begins : " . date('r') );


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



/**
 *
 * Returns User object
 *
 * @param $what -
 *      if it is numeric, then it is considered as idx
 *      if it is string, then it is considered as id
 * @return \model\user\User
 *
 */
function user( $what = null ) {
    $user = new \model\user\User();
    if ( $what ) {
        $user->load( $what );
    }
    return $user;
}

/**
 * Returns currently logged in user Object.
 *
 *
 * @warning if there is no $_REQUEST['session_id'], then the User object is empty.
 * @return \model\user\User
 */
$_currentUser = get_current_user();            // Empty User Object
function setCurrentUser( $user ) {
    global $_currentUser;
    $_currentUser = $user;
}


/**
 * @return \model\user\User
 */
function currentUser()
{
    global $_currentUser;
    if ( ! $_currentUser instanceof \model\user\User) {   // If there no current User,
        if ( in('session_id') ) {                       // If session_id has passed,
            setCurrentUser(user(in('session_id')));     // set current user.
        }
        else {
            user()->forceLogin( ANONYMOUS_ID );
        }
    }
    return $_currentUser;
}


function entity() {
    return new \model\entity\Entity();
}


/**
 *
 *
 * @return \model\meta\Meta
 *
 */
function meta() {
    return new \model\meta\Meta();
}
function meta_injector( $model, $model_idx ) {
    return new \model\meta\Meta_Injector( $model, $model_idx);
}

function config( $what = null ) {
    $config = new \model\forum\Forum_Config();

    if ( $what ) {
        $config->load( $what );
    }
    return $config;
}

/**
 * @param null $what
 * @return \model\forum\Forum_Post
 */
function post( $what = null ) {
    $post = new \model\forum\Forum_Post();
    if ( $what ) {
        $post->load( $what );
    }
    return $post;

}


/**
 * @return bool|\model\database\Database|null
 */
function db() {
    return \model\database\Database::load();
}

