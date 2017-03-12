<?php


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
 * HTTP 로 넘오 온 변수 중 'required' 변수들의 '키'만 배열로 리턴한다. 값은 포함되지 않는다.
 *
 * @return array
 */
function get_keys_of_required_variables() {
    $route = get_current_route();
    if ( ! isset( $route['variables'] ) ) return [];
    $variables = $route['variables'];
    if ( ! isset( $variables['required'] ) ) return [];
    return $variables['required'];
}


/**
 *
 * HTTP 로 넘오 온 변수 중 'optional' 변수들의 '키'만 배열로 리턴한다. 값은 포함되지 않는다.
 *
 * @return array
 */
function get_keys_of_optional_variables() {
    $route = get_current_route();
    if ( ! isset( $route['variables'] ) ) return [];
    $variables = $route['variables'];
    if ( ! isset( $variables['optional'] ) ) return [];
    return $variables['optional'];
}


/**
 *
 * HTTP 로 넘오 온 변수 중 'system' 변수들의 '키'만 배열로 리턴한다. 값은 포함되지 않는다.
 *
 * @return array
 */
function get_keys_of_system_variables() {
    $route = get_current_route();
    $system = [];
    if ( array_key_exists( 'variables', $route ) ) {
        $variables = $route['variables'];
        if ( array_key_exists( 'system', $variables ) ) $system = $variables['system'];
    }



    /**
     *
     *
     * 'route' variable is accepted by default since all access needs a route.
     *
     */
    if ( ! in_array( 'route', $system ) ) $system[] = 'route';

    return $system;
}


/**
 *
 * HTTP 로 넘어 온 변수들의 '키'만 배열로 리턴한다. 값은 포함되지 않는다.
 *
 * @return array
 */
function get_keys_of_variables() {



    $all = array_merge(
        get_keys_of_required_variables(),
        get_keys_of_optional_variables(),
        get_keys_of_system_variables()
    );



    return $all;


}

/**
 *
 *
 * Checks if the HTTP variables are properly passed.
 *
 *
 *
 * @return array|bool
 *
 */
function check_http_variables() {

    if ( $required = get_keys_of_required_variables()) {
        foreach ( $required as $v ) {
            if ( array_key_exists( $v, $_REQUEST ) ) {

            }
            else {
                return [ 'code' => ERROR_REQUIRED_INPUT_IS_MISSING, 'message' => "required-variable-is-missing $v" ];
            }
        }
    }

    $variables = get_keys_of_variables();
    foreach( array_keys( $_REQUEST ) as $key ) {
        if ( in_array( $key, $variables ) ) {

        }
        else {
            return [ 'code' => ERROR_INVALID_INPUT_VARIABLE, 'message' => "$key is-passed-over-http-but-NOT-in-route-variables"];
        }
    }
    return OK;
}


/**
 * @return array|int
 *
 */
function check_http_variables_type() {

    $number_if_contain = [ 'idx' ];
    $string = [ 'route', 'session', 'id' ]; // cannot be empty if passed.

    // title, content, data can be a numeric, string, empty, boolean.


    //$keys = get_route_variables();

    foreach( $_REQUEST as $k => $v ) {

        foreach ( $number_if_contain as $contain ) {
            if ( strpos( $k, $contain ) !== false ) {
                if ( ! is_numeric($v) ) return [ 'code' => ERROR_MALFORMED_VARIABLE_NUMBER, 'message' => "variable $k must be number"];
            }
        }

        if ( in_array( $k, $string ) ) {
            if ( empty($v) ) return [ 'code' => ERROR_VARIABLE_EMPTY, 'message' => "variable $k must not be empty"];
            if ( is_numeric($v) ) return [ 'code' => ERROR_VARIABLE_NUMERIC, 'message' => "variable $k must not be numeric"];
            if ( is_array($v) ) return [ 'code' => ERROR_VARIABLE_ARRAY, 'message' => "variable $k must not be array"];
        }
    }


    return OK;
}




/**
 * @param $name
 * @return mixed
 *      return FALSE if there is no route.
 */
function get_route( $name ) {
    global $routes;
    if ( isset( $routes[ $name ] ) && $routes[ $name ] ) return $routes[ $name ];
    else return FALSE;
}

function add_route( $name, $option ) {
    global $routes;
    $routes[ $name ] = $option;
}
$_current_route = null;
function set_current_route( $route ) {
    global $_current_route;
    $_current_route = $route;
}
function get_current_route( ) {
    global $_current_route;
    return $_current_route;
}


/**
 *
 * Returns an array of HTTP variables ( GET, POST ) based on route's 'required' and 'optional' without 'system'
 * If you need to get only key, use 'get_keys_of_variables()'.
 *
 * @translate-korean HTTP 로 전달된 GET, POST 의 키와 값을 배열로 리턴한다. 키만 필요한 경우 get_keys_of_variables() 를 사용한다.
 *
 * @note use get_keys_
 *
 * @note this returns Actual HTTP Data.
 *
 * @return array
 *
 */
function get_route_variables( ) {

    $ret = [];
    $route = get_current_route();

    if ( ! isset( $route['variables'] ) ) return $ret;

    $variables = $route['variables'];


    $required = $optional = [];
    if ( isset( $variables['required'] ) ) $required = $variables['required'];
    if ( isset( $variables['optional'] ) ) $optional = $variables['optional'];
    $accepts = array_merge( $required, $optional );

    foreach( $accepts as $k ) {
        if ( array_key_exists( $k, $_REQUEST ) ) $ret[ $k ] = $_REQUEST[ $k ];
    }
    return $ret;
}

/**
 *
 * Returns an array of HTTP input based on route's 'optional' variable.
 *
 * @return array
 */
function get_route_optional_variables() {
    $ret = [];
    $route = get_current_route();

    if ( ! isset( $route['variables'] ) || ! isset( $route['variables']['optional'] ) ) return $ret;

    $optional = $route['variables']['optional'];
    foreach( $optional as $k ) {
        if ( array_key_exists( $k, $_REQUEST ) ) $ret[ $k ] = $_REQUEST[ $k ];
    }
    return $ret;
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
 *
 * @warning if there is no $_REQUEST['session_id'], then the User object is empty.
 * @return \model\user\User
 */
$_currentUser = null;
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

    // If not logged in or session id has changed. Session id changes when test.
    if ( $_currentUser === null || $_currentUser->session_id != in('session_id') ) {
        if ( in('session_id') ) {                       // If session_id has passed,
            setCurrentUser( user( in('session_id') ) );     // set current user.
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

function taxonomy() {
    return new \model\taxonomy\Taxonomy();
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
 * @return bool|\model\database\Database|null
 */
function db() {
    return \model\database\Database::load();
}

