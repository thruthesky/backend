<?php



$route_files = rsearch( __MODEL_DIR__, '_route.php' );
//di($route_files);
$routes = [];
foreach (  $route_files as $file ) {
    include $file;
}

//di($routes);


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
 *
 * @note this returns Actual HTTP Data.
 *
 * @return array
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

