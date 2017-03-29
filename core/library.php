<?php



$_is_test = false;
function is_test(){
    global $_is_test;
    return $_is_test;
}

function set_test(){
    global $_is_test;
    $_is_test = true;
}



/**
 *
 * Returns Truthy value if the input is error response.
 *
 * @param $code - is one of the responses
 *      1. HTTP call
 *      2. Fake Http call
 *      3. error number.
 *
 *
 *
 * @return mixed
 *      - number of ERROR CODE if there is error.
 *      - TRUE if $res is 'FALSE'(ERROR)
 */
function is_error ( $res ) {
    if ( is_numeric( $res ) && $res < 0 ) return $res;
    else if ( is_array( $res ) && isset( $res['code'] ) && $res['code'] < 0 ) return $res['code'];
    else if ( $res === FALSE ) return TRUE;
    else return false;
}



/**
 *
 * Returns true if the $re is a success.
 *
 * @note if $re['code'] has number less than 0, then it returns FALSE.
 * @note if $re is number and it is less than 0, then it returns FALSE.
 *
 *
 * @Warning sometimes, 0 can be success. so, where there is error, it should always return FALSE.
 *          0 is defined as OK
 *          FALSE is defined as BAD.
 *
 *
 * @attention the input can be a response or a primitive value.
 *
 * @param $re
 * @return bool
 */
function is_success( $re ) {

    if ( is_array( $re ) ) {
        //di("yes array");
        if ( isset( $re['code'] ) ) {
            //di("yes code is set");
            if ( $re['code'] == 0 ) {       // if code is 0.
                //di("yes code is 0");
                return TRUE;
            }
            else return FALSE;              // if code is NOT 0.
        }
    }

    if ( is_numeric( $re ) && $re < 0 ) return FALSE;
    if ( $re === ERROR ) return FALSE;

    if ( $re === OK ) return TRUE;

    if ( empty($re) ) return FALSE;

    return TRUE;
}


function die_if_table_not_exist( $_table_name ) {

    if ( db()->tableExists( $_table_name ) ) {

    }
    else {
        die("failed to install $_table_name table");
    }

}


function table_post_deleted() {
    return DATABASE_PREFIX . 'post_data_deleted';
}





/**
 *
 *
 *
 * @param $name
 * @return mixed
 *      return FALSE if there is no route.
 */
function get_route( $name ) {
    return route()->getRoute( $name );
    /*
    global $routes;
    if ( isset( $routes[ $name ] ) && $routes[ $name ] ) return $routes[ $name ];
    else return FALSE;
    */
}

/**
 *
 *
 * @deprecated use route()->add()
 *
 * @param $name
 * @param $option
 * @return \model\route\Route
 */
function add_route( $name, $option ) {
    return route()->addRoute( $name, $option );
    /*
    global $routes;
    $routes[ $name ] = $option;
    */
}

/**
 *
 * @deprecated use Route class()
 */
function set_current_route( $route ) {
    return route()->set_current_route( $route );
    /*
    global $_current_route;
    $_current_route = $route;
    */
}

/**
 * @return mixed
 * @deprecated use Route class()
 */
function get_current_route( ) {
    return route()->get_current_route();
    /*
    global $_current_route;
    return $_current_route;
    */
}


/**
 * Returns a url of installed 'root folder'.
 *
 * @return mixed
 *
 *      - http://abc.org
 *      - http://abc.def.ghi.com/backend
 */
function get_site_url() {
    $pu = pathinfo( current_script_url() );
    return $pu['dirname'];
}

function get_index_php_url() {
    return get_site_url() . '/index.php';
}




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
 * @Attention IF there is error here, double check if the session_id has been invalidated.
 *
 * @note
 *
 * 중요 : setCurrentUser() 를 한 다음에 currentUser() 를 하면,
 * currentUser() 에서 $_REQUEST['session_id'] 값과 setCurrentUser() 에 기록된 User->session_id 를 비교해서
 * 틀리면 다시 $_REQUEST['session_id'] 의 사용자가 리턴된다. 이것은 테스트를 할 때 빈번하게 사용된다
 *
 * setCurrentUser() 를 하면 아예 $_REQUEST['session_id'] 를 변경해 버린다.
 *
 *
 * @warning if there is no $_REQUEST['session_id'], then the User object is empty.
 * @return \model\user\User
 */
$_currentUser = null;
function setCurrentUser( $user ) {
    global $_currentUser;
    $_currentUser = $user;
    $_REQUEST['session_id'] = $_currentUser->session_id;
}


/**
 *
 *
 * @Attention IF there is error here, double check if the session_id has been invalidated.
 *
 * @return \model\user\User
 */
function currentUser()
{
    global $_currentUser;


    // If not logged in or session id has changed. Session id changes when test.
    if ( $_currentUser === null || $_currentUser->session_id != in('session_id')) {

        // debug_log("------------ d ?");
        if ( in('session_id') ) {                       // If session_id has passed,

            $user = user( in('session_id') );
            if ( ! $user->exist() ) {
                /**
                 * If session_id is wrong, it exists here !!
                 */
                exit( error( ERROR_WRONG_SESSION_ID ) );
            }
            setCurrentUser( $user );     // set current user.
        }
        else {


            user()->forceLogin( ANONYMOUS_ID );
        }
    }

    return $_currentUser;
}

function anonymousUser() {
    return user( ANONYMOUS_ID );
}

// first test user
function testUser() {
    return user( TEST_USER_ID );
}

// second test user
function thruthesky() {
    return user( 'thruthesky' );
}



function entity() {
    return new \model\entity\Entity();
}

function taxonomy() {
    return new \model\taxonomy\Taxonomy();
}


/**
 * @return \model\route\Route
 */
function route() {
    return new \model\route\Route();
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
function meta_proxy( $model, $model_idx ) {
    return new \model\meta\Meta_Proxy( $model, $model_idx);
}

/**
 * @param null $what
 * @return \model\post\Post_Config
 */
function config( $what = null ) {
    $config = new \model\post\Post_Config();

    if ( $what ) {
        $config->load( $what );
    }
    return $config;
}

/**
 * @param null $what
 * @return \model\post\Post_Data
 */
function post( $what = null ) {
    $post = new \model\post\Post_Data();
    if ( $what ) {
        $post->load( $what );
    }
    return $post;
}

/**
 * @param null $what
 * @return \model\post\Post_Comment
 */
function comment( $what = null ) {
    $obj = new \model\post\Post_Comment();
    if ( $what ) {
        $obj->load( $what );
    }
    return $obj;
}


function f( $what = null ){
    $obj = new \model\file\File();
    if ( $what ) {
        $obj->load($what);
    }
    return $obj;
}

function file_proxy( $model, $model_idx ) {
    return new \model\file\File_Proxy( $model, $model_idx);
}


/**
 *
 *
 * @param null $what
 * @return \model\category\Category
 *
 *
 */
function category( $what = null ){
    $obj = new \model\category\Category();
    if ( $what ) {
        $obj->load($what);
    }
    return $obj;
}




/**
 * @return bool|\model\database\Database|null
 */
function db() {
    return \model\database\Database::load();
}

function hook() {
    return new \model\hook\Hook();
}


/**
 * @return \model\system\System
 */
function backend() {
    return new \model\system\System();
}



function test( $re, $code ) {
    \model\test\Test::test( $re, $code );
}
